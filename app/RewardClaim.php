<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Thumbnail;
use App\SendMessage;
use DB;
use URL;

class RewardClaim extends Eloquent
{
    use SoftDeletes;

    protected $table = 'reward_claims';
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'user_id'            => 'integer',
        'dealer_id'          => 'integer',
        'reward_product_id'  => 'integer',
        'customer_points_id' => 'integer',
        'points_spent'       => 'integer',
        'quantity'           => 'integer',
        'points_per_unit'    => 'integer',
    ];

    function __construct()
    {
        parent::__construct();
        $this->attributes = array('ip' => Thumbnail::getclientip());
    }

    public function product()
    {
        return $this->belongsTo(RewardProduct::class, 'reward_product_id');
    }

    /**
     * Human readable stage, shared by the app history and the admin screens.
     */
    public static function statusLabel($status)
    {
        $labels = array(
            'Pending'    => 'Waiting for approval',
            'Approved'   => 'Approved, preparing dispatch',
            'Dispatched' => 'Sent to your dealer',
            'Delivered'  => 'Collected from your dealer',
            'Rejected'   => 'Rejected',
        );

        return isset($labels[$status]) ? $labels[$status] : $status;
    }

    /**
     * A 6-digit pickup code the carpenter reads out to the dealer, and the
     * dealer's WhatsApp bot verifies. Retries on the rare collision with
     * another code that is still active (not yet verified).
     */
    public static function generateRedemptionCode()
    {
        for ($i = 0; $i < 5; $i++) {
            $code = (string) mt_rand(100000, 999999);
            $exists = self::where('redemption_code', $code)
                ->whereNull('code_verified_at')
                ->exists();
            if (!$exists) {
                return $code;
            }
        }
        return (string) mt_rand(100000, 999999);
    }

    /**
     * Dispatched/Approved -> Delivered, shared by the admin's manual "Mark
     * Delivered" action and the dealer's WhatsApp bot code verification.
     */
    public function markDelivered($remark = null, $codeVerified = false)
    {
        $this->status           = 'Delivered';
        $this->delivered_at     = date('Y-m-d H:i:s');
        $this->delivered_remark = $remark;
        if ($codeVerified) {
            $this->code_verified_at = date('Y-m-d H:i:s');
        }
        $this->save();

        SendMessage::getSendMessage('Redemption Delivered', $this->user_id, $this->points_spent, 'user', ['claim' => $this]);
    }

    /************************** Api function Starts ****************************/

    #=>=>=>=>=>=>=>=>Claim one or more Reward Products against Points=>=>=>=>=>=>#
    /**
     * Accepts either a single product ...
     *
     *     reward_product_id = 12, quantity = 2
     *
     * ... or a basket of products, each with its own quantity:
     *
     *     items = [{"reward_product_id": 12, "quantity": 2}, {"reward_product_id": 9}]
     *
     * The whole basket is validated before a single point is deducted, then every
     * line becomes its own claim row so the admin can approve, dispatch and reject
     * each product on its own. Rows of one submission share a claim_group_id.
     */
    public static function claimProduct($params)
    {
        $result = array();
        $status = 'error';
        $msg    = '';

        try {
            $items = self::normaliseClaimItems($params);

            if (empty($items)) {
                $result['replyStatus']  = false;
                $result['replyMessage'] = 'Please select at least one product to claim.';
                return $result;
            }

            $user = User::where('id', $params['user_id'])->first();
            if (empty($user)) {
                $result['replyStatus']  = false;
                $result['replyMessage'] = 'User not found.';
                return $result;
            }

            #====The product is handed over by the carpenter's dealer, so a=====#
            #====valid dealer link is mandatory before any points are spent.====#
            $dealerUserId  = $user->dealer_id;
            $isValidDealer = $dealerUserId ? User::checkValidDealer($dealerUserId) : 'No';

            if ($isValidDealer != 'Yes') {
                $result['replyStatus']  = false;
                $result['replyMessage'] = 'No dealer is linked to your account. Please contact support before claiming a product.';
                return $result;
            }

            #====Validate every line before anything is charged=================#
            $lines       = array();
            $totalPoints = 0;

            foreach ($items as $item) {
                $product = RewardProduct::where('id', $item['reward_product_id'])
                    ->where('status', 1)->first();

                if (empty($product)) {
                    $result['replyStatus']  = false;
                    $result['replyMessage'] = 'One of the selected products is no longer available.';
                    return $result;
                }

                $quantity = $item['quantity'];

                if ($product->stock !== null && $product->stock <= 0) {
                    $result['replyStatus']  = false;
                    $result['replyMessage'] = $product->name.' is out of stock.';
                    return $result;
                }

                if ($product->stock !== null && $product->stock < $quantity) {
                    $result['replyStatus']  = false;
                    $result['replyMessage'] = 'Only '.$product->stock.' unit(s) of '.$product->name.' are left in stock.';
                    return $result;
                }

                $pointsPerUnit = (int) $product->points_required;
                $linePoints    = $pointsPerUnit * $quantity;
                $totalPoints  += $linePoints;

                $lines[] = array(
                    'product'         => $product,
                    'quantity'        => $quantity,
                    'points_per_unit' => $pointsPerUnit,
                    'points'          => $linePoints,
                );
            }

            #====Current balance of the user===================================#
            $balance = @CustomerPoints::select('current_points')
                ->where('user_id', $params['user_id'])
                ->orderBy('id', 'desc')->first()->current_points;

            $balance = (int) $balance;

            #====The whole basket has to be covered by the balance=============#
            if ($balance < $totalPoints) {
                $result['replyStatus']      = false;
                $result['replyMessage']     = count($lines) > 1
                    ? 'You do not have sufficient reward points to claim these products.'
                    : 'You do not have sufficient reward points to claim this product.';
                $result['available_points'] = $balance;
                $result['points_needed']    = $totalPoints - $balance;
                return $result;
            }

            #====Snapshot the dealer counter the products will be sent to======#
            $dealerUser   = User::where('id', $dealerUserId)->first();
            $dealerDetail = Dealer::where('user_id', $dealerUserId)->first();

            $groupId       = 'CLM'.date('ymdHis').mt_rand(100, 999);
            $runningPoints = $balance;
            $ledgerIds     = array();
            $claims        = array();

            # tbl_customer_points is MyISAM, so the deductions are NOT covered by any
            # transaction. If a claim row below fails we undo them by hand, otherwise
            # the carpenter is charged for a claim that was never recorded.
            try {
                DB::beginTransaction();

                foreach ($lines as $line) {
                    $product = $line['product'];

                    #====Deduct the points on the existing points ledger========#
                    # reward_status is the state of the POINT DEDUCTION, which is final
                    # the moment the claim is placed - the delivery lifecycle lives on
                    # the claim row instead. Leaving it 'pending' would drop this row
                    # into the retired cash redemption work queues, which filter on
                    # reward_status='pending'. One ledger row per product keeps the
                    # per-product refund on rejection exact.
                    $runningPoints -= $line['points'];

                    $obj = new CustomerPoints();
                    $obj->user_id          = $params['user_id'];
                    $obj->dealer_id        = $dealerUserId;
                    $obj->transaction_type = 'Redeem';
                    $obj->point            = $line['points'];
                    $obj->current_points   = $runningPoints;
                    $obj->reward_status    = 'approved';
                    $obj->description      = 'Reward Product Claim - '.$product->name.($line['quantity'] > 1 ? ' x '.$line['quantity'] : '');
                    $obj->save();

                    $ledgerIds[] = $obj->id;

                    #====Store the claim / fulfilment record====================#
                    $claim = new self();
                    $claim->claim_group_id     = $groupId;
                    $claim->user_id            = $params['user_id'];
                    $claim->reward_product_id  = $product->id;
                    $claim->customer_points_id = $obj->id;
                    $claim->product_name       = $product->name;
                    $claim->quantity           = $line['quantity'];
                    $claim->points_per_unit    = $line['points_per_unit'];
                    $claim->points_spent       = $line['points'];

                    $claim->dealer_id      = $dealerUserId;
                    $claim->dealer_name    = $dealerDetail ? $dealerDetail->dealer_name : (isset($dealerUser->full_name) ? $dealerUser->full_name : null);
                    $claim->dealer_mobile  = $dealerDetail ? $dealerDetail->mobile_no : (isset($dealerUser->mobileno) ? $dealerUser->mobileno : null);
                    $claim->dealer_address = $dealerDetail ? $dealerDetail->address : null;
                    $claim->dealer_city    = $dealerDetail ? $dealerDetail->city : null;
                    $claim->dealer_pincode = $dealerDetail ? $dealerDetail->pincode : null;

                    $claim->status     = 'Pending';
                    $claim->added_from = 'app';
                    $claim->save();

                    $claims[] = $claim;

                    #====Reduce stock when the product tracks it================#
                    if ($product->stock !== null) {
                        RewardProduct::where('id', $product->id)->decrement('stock', $line['quantity']);
                    }
                }

                DB::commit();
            } catch (\Exception $inner) {
                DB::rollBack();
                if (!empty($ledgerIds)) {
                    CustomerPoints::whereIn('id', $ledgerIds)->forceDelete();
                }
                throw $inner;
            }

            $sendMSG = SendMessage::getSendMessage('Redeem Points', $params['user_id'], $totalPoints, 'user', ['claim' => $claims[0]]);

            #====One message for the whole request, listing every product=====#
            SendMessage::getSendMessage('Redemption Request Received', $params['user_id'], $totalPoints, 'user', ['claim' => $claims[0], 'claims' => $claims]);

            $firstClaim   = $claims[0];
            $claimedItems = array();
            $totalUnits   = 0;

            foreach ($claims as $claim) {
                $totalUnits += $claim->quantity;
                $claimedItems[] = array(
                    'claim_id'        => $claim->id,
                    'product_name'    => $claim->product_name,
                    'quantity'        => (int) $claim->quantity,
                    'points_per_unit' => (int) $claim->points_per_unit,
                    'points_spent'    => (int) $claim->points_spent,
                );
            }

            $result['claim_group_id']   = $groupId;
            $result['claim_id']         = $firstClaim->id;
            $result['items']            = $claimedItems;
            $result['total_products']   = count($claims);
            $result['total_quantity']   = $totalUnits;
            $result['points_spent']     = $totalPoints;
            $result['point']            = $balance - $totalPoints;
            $result['available_points'] = $balance - $totalPoints;
            $result['dealer_name']      = $firstClaim->dealer_name;
            $status = 'success';

            $what = count($claims) > 1
                ? $totalUnits.' item(s) across '.count($claims).' products'
                : $firstClaim->product_name.($firstClaim->quantity > 1 ? ' (x'.$firstClaim->quantity.')' : '');

            $msg = 'Your claim for '.$what.' has been submitted. Once approved it will be sent to '.$firstClaim->dealer_name.' for collection.';
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $status = 'error';
            $msg = "Error : ".$e->getMessage();
        } catch (\Exception $e) {
            DB::rollBack();
            $status = 'error';
            $msg = $e->getMessage();
        }

        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus']  = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }

    /**
     * Turns whatever the app posted into a clean list of
     * ['reward_product_id' => int, 'quantity' => int] lines.
     *
     * The same product sent twice is merged into one line so the stock and point
     * checks see the real total instead of two independent halves.
     */
    protected static function normaliseClaimItems($params)
    {
        $raw = array();

        if (isset($params['items']) && $params['items'] !== '' && $params['items'] !== null) {
            $raw = $params['items'];

            # form-encoded posts send the basket as a JSON string
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $raw = is_array($decoded) ? $decoded : array();
            }
        } elseif (isset($params['reward_product_id']) && $params['reward_product_id']) {
            $raw = array(array(
                'reward_product_id' => $params['reward_product_id'],
                'quantity'          => isset($params['quantity']) ? $params['quantity'] : 1,
            ));
        }

        if (!is_array($raw)) {
            return array();
        }

        $items = array();

        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }

            $productId = isset($row['reward_product_id']) ? (int) $row['reward_product_id'] : 0;
            $quantity  = isset($row['quantity']) && $row['quantity'] !== '' && $row['quantity'] !== null
                ? (int) $row['quantity'] : 1;

            if ($productId < 1 || $quantity < 1) {
                continue;
            }

            if (isset($items[$productId])) {
                $items[$productId]['quantity'] += $quantity;
            } else {
                $items[$productId] = array('reward_product_id' => $productId, 'quantity' => $quantity);
            }
        }

        return array_values($items);
    }
    #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#

    #=>=>=>=>=>=>=>=>Claim history of the logged in user=>=>=>=>=>=>=>=>=>=>=>=>#
    public static function claimHistory($params)
    {
        $result = array();
        $status = 'error';
        $msg    = '';

        try {
            $imagePath = URL::to('uploads/reward-products');

            $data = self::leftjoin('reward_products', 'reward_products.id', 'reward_claims.reward_product_id')
                ->select(
                    'reward_claims.id',
                    'reward_claims.claim_group_id',
                    'reward_claims.product_name',
                    'reward_claims.quantity',
                    'reward_claims.points_per_unit',
                    'reward_claims.points_spent',
                    'reward_claims.status',
                    'reward_claims.redemption_code',
                    'reward_claims.admin_remark',
                    'reward_claims.dealer_name',
                    'reward_claims.dealer_mobile',
                    'reward_claims.dealer_address',
                    'reward_claims.dealer_city',
                    'reward_claims.courier_name',
                    'reward_claims.tracking_number',
                    'reward_claims.dispatched_at',
                    'reward_claims.delivered_at',
                    'reward_claims.created_at',
                    DB::raw("IF(STRCMP(tbl_reward_products.image,''),CONCAT('".$imagePath."/"."',tbl_reward_products.image),'') as image", false)
                )
                ->where('reward_claims.user_id', $params['user_id'])
                ->orderBy('reward_claims.id', 'desc')->get();

            foreach ($data as $key => $value) {
                $value->status_label = self::statusLabel($value->status);

                #====Only show the code while it can still be redeemed. It is====#
                #====generated on Dispatch, so Approved never has one anyway.====#
                if ($value->status != 'Dispatched') {
                    $value->redemption_code = null;
                }

                if ($value->status == 'Delivered') {
                    $value->icon  = 'checkbox-marked-circle-outline';
                    $value->color = '#53B902';
                } elseif ($value->status == 'Rejected') {
                    $value->icon  = 'close';
                    $value->color = '#D42018';
                } elseif ($value->status == 'Dispatched') {
                    $value->icon  = 'truck-delivery';
                    $value->color = '#0F9BD7';
                } else {
                    $value->icon  = 'circle-outline';
                    $value->color = 'grey';
                }

                #====Tell the carpenter where to collect it - only once the====#
                #====product has actually reached the dealer (Dispatched),====#
                #====which is also when the pickup code is generated.=========#
                $value->collect_from = in_array($value->status, array('Dispatched', 'Delivered'))
                    ? $value->dealer_name : '';
            }

            $result['data'] = $data;
            $status = 'success';
            $msg    = '';
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }

        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus']  = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }
    #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
}
