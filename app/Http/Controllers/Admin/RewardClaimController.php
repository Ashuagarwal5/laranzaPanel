<?php

namespace App\Http\Controllers\Admin;

use App\CustomerPoints;
use App\RewardClaim;
use App\RewardProduct;
use App\SendMessage;
use App\User;
use App\WebsiteSetting;
use Datatables;
use DB;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;

class RewardClaimController extends CodespurController
{
    protected $manager = 'Product Claim';
    protected $PARENT_ID = 124;

    /*
    |--------------------------------------------------------------------------
    | Listing screens - one per stage of the fulfilment chain
    |
    | Pending -> Approved -> Dispatched (to dealer) -> Delivered (to carpenter)
    | with Rejected available until the product leaves for the dealer.
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return $this->listScreen('Pending', 'Pending Claims');
    }

    public function approvedList()
    {
        return $this->listScreen('Approved', 'Approved Claims');
    }

    public function dispatchedList()
    {
        return $this->listScreen('Dispatched', 'Sent To Dealer');
    }

    public function deliveredList()
    {
        return $this->listScreen('Delivered', 'Delivered Claims');
    }

    public function rejectedList()
    {
        return $this->listScreen('Rejected', 'Rejected Claims');
    }

    protected function listScreen($status, $manager_name)
    {
        $PARENT_ID  = $this->PARENT_ID;
        $route_data = route('admin.reward-claims.data', ['status' => $status]);

        return view('admin.reward-claims.list', compact('PARENT_ID', 'manager_name', 'route_data', 'status'));
    }

    public function data(Request $request, $status = 'Pending')
    {
        $prefix = DB::getTablePrefix();

        # Scoped to the list's own status ("Approved" on the Approved list,
        # "Dispatched" on the Sent To Dealer list, ...) so the figure actually
        # differs page to page - an unscoped lifetime total looked identical
        # (and so broken) everywhere the same carpenter had several claims.
        $validStatuses = array('Pending', 'Approved', 'Dispatched', 'Delivered', 'Rejected');
        $statusForTotals = in_array($status, $validStatuses) ? $status : 'Pending';

        $claimedUnits = "(SELECT COALESCE(SUM(c2.quantity), 0) FROM {$prefix}reward_claims c2
            WHERE c2.user_id = {$prefix}reward_claims.user_id
              AND c2.status = '{$statusForTotals}'
              AND c2.deleted_at IS NULL) as user_claimed_units";

        $claimedOrders = "(SELECT COUNT(*) FROM {$prefix}reward_claims c3
            WHERE c3.user_id = {$prefix}reward_claims.user_id
              AND c3.status = '{$statusForTotals}'
              AND c3.deleted_at IS NULL) as user_claimed_orders";

        # How many products came in on the same submission, so the admin can tell a
        # single-product claim from one line of a multi-product basket at a glance.
        $groupSize = "(SELECT COUNT(*) FROM {$prefix}reward_claims c4
            WHERE c4.claim_group_id IS NOT NULL
              AND c4.claim_group_id = {$prefix}reward_claims.claim_group_id
              AND c4.deleted_at IS NULL) as group_size";

        $data = RewardClaim::join('users', 'users.id', 'reward_claims.user_id')
            ->select(
                'reward_claims.*',
                'users.full_name as user_name',
                'users.mobileno as user_mobile',
                DB::raw($claimedUnits, false),
                DB::raw($claimedOrders, false),
                DB::raw($groupSize, false),
                DB::raw("DATE_FORMAT(tbl_reward_claims.created_at, '%d %M %Y') as add_date")
            )
            ->where('reward_claims.status', $status);

        $actions = '<a class="btn btn-info btn-xs" data-toggle="modal" data-target="#modal-regular" title="View Claim" href="{{ route("admin.reward-claims.view", ["id" => $id]) }}"><i class="fa fa-eye"></i></a> ';

        if ($status == 'Pending') {
            $actions .= '<a class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-regular" title="Approve Claim" href="{{ route("admin.reward-claims.approve.modal", ["id" => $id]) }}"><i class="fa fa-check"></i></a> ';
            $actions .= '<a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-regular" title="Reject Claim" href="{{ route("admin.reward-claims.reject.modal", ["id" => $id]) }}"><i class="fa fa-ban"></i></a>';
        } elseif ($status == 'Approved') {
            # No reject here - the pickup code is already generated and may
            # already be in the carpenter's hands by this point.
            $actions .= '<a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-regular" title="Send To Dealer" href="{{ route("admin.reward-claims.dispatch.modal", ["id" => $id]) }}"><i class="fa fa-truck"></i></a>';
        }
        # No manual "Mark Delivered" action - delivery is now only confirmed
        # by the dealer verifying the carpenter's pickup code over WhatsApp
        # (see WhatsappBotService), so the admin panel can't fake a handover
        # that never actually happened.

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('quantity', function ($claim) {
                return max(1, (int) $claim->quantity);
            })
            ->editColumn('product_name', function ($claim) {
                if ((int) $claim->group_size > 1) {
                    return e($claim->product_name)
                        .'<br><small class="text-muted">1 of '.(int) $claim->group_size.' products in '.e($claim->claim_group_id).'</small>';
                }

                return e($claim->product_name);
            })
            ->addColumn('user_claimed', function ($claim) {
                return (int) $claim->user_claimed_units.' item(s) / '.(int) $claim->user_claimed_orders.' claim(s)';
            })
            ->addColumn('actions', $actions)
            ->rawColumns(['actions', 'product_name'])
            ->make(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Claim detail
    |--------------------------------------------------------------------------
    */

    public function view($id)
    {
        $claim = RewardClaim::join('users', 'users.id', 'reward_claims.user_id')
            ->select('reward_claims.*', 'users.full_name as user_name', 'users.mobileno as user_mobile')
            ->where('reward_claims.id', $id)->first();

        if (empty($claim)) {
            return '<div class="modal-body">Data Not Found</div>';
        }

        $product = RewardProduct::withTrashed()->find($claim->reward_product_id);

        #====Everything this carpenter has pulled out of the catalog so far====#
        $summary = RewardClaim::where('user_id', $claim->user_id)
            ->where('status', '!=', 'Rejected')
            ->select(
                DB::raw('COUNT(*) as total_claims'),
                DB::raw('COALESCE(SUM(quantity), 0) as total_units'),
                DB::raw('COALESCE(SUM(points_spent), 0) as total_points')
            )->first();

        #====Other products submitted in the same basket=======================#
        $groupItems = collect();
        if (!empty($claim->claim_group_id)) {
            $groupItems = RewardClaim::where('claim_group_id', $claim->claim_group_id)
                ->orderBy('id', 'asc')->get();

            if ($groupItems->count() < 2) {
                $groupItems = collect();
            }
        }

        return View('admin.reward-claims.view', compact('claim', 'product', 'summary', 'groupItems'));
    }

    /*
    |--------------------------------------------------------------------------
    | Pending -> Approved
    |--------------------------------------------------------------------------
    */

    public function approveModal($id)
    {
        $detail = RewardClaim::find($id);
        $model  = 'Approve Product Claim';
        $error  = empty($detail) ? 'Data Not Found' : null;
        $type   = !empty($detail)
            ? 'approve this claim for '.$detail->product_name.'. It will then be sent to '.$detail->dealer_name
            : 'approve this claim';
        $confirm_route = route('admin.reward-claims.approve', ['id' => $id]);

        return View('admin/layouts/status_modal_confirmation', compact('error', 'type', 'confirm_route', 'model'));
    }

    public function approve($id)
    {
        $claim = RewardClaim::find($id);
        if (empty($claim) || $claim->status != 'Pending') {
            return redirect()->route('admin.reward-claims')
                ->with('error', 'This is not a valid action.');
        }

        $claim->status = 'Approved';
        $claim->save();

        SendMessage::getSendMessage('Redemption Approval', $claim->user_id, $claim->points_spent, 'user', ['claim' => $claim]);

        return redirect()->route('admin.reward-claims.approved.list')
            ->with('success', 'Claim approved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Approved -> Dispatched to the dealer
    |--------------------------------------------------------------------------
    */

    public function dispatchModal($id)
    {
        $claim = RewardClaim::find($id);
        $error = empty($claim) ? 'Data Not Found' : null;
        $confirm_route = route('admin.reward-claims.dispatch', ['id' => $id]);

        return View('admin.reward-claims.dispatch_modal', compact('error', 'claim', 'confirm_route'));
    }

    public function dispatchToDealer(Request $request, $id)
    {
        $claim = RewardClaim::find($id);
        if (empty($claim) || $claim->status != 'Approved') {
            return response()->json([
                'status'     => 'error',
                'error_msg'  => 'This is not a valid action.',
                'slideToTop' => true,
            ]);
        }

        $rules = [
            'courier_name'    => 'required|max:255',
            'tracking_number' => 'required|max:255',
            'admin_remark'    => 'nullable|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errorArray' => $validator->errors(),
                'error_msg'  => 'Oops ! Please fill required fields.',
                'slideToTop' => true,
            ]);
        }

        $claim->status             = 'Dispatched';
        $claim->courier_name       = $request->courier_name;
        $claim->tracking_number    = $request->tracking_number;
        $claim->dispatched_at      = date('Y-m-d H:i:s');
        # The pickup code is only meaningful once the product is actually at
        # the dealer, so it is generated here rather than on approval.
        $claim->redemption_code    = RewardClaim::generateRedemptionCode();
        $claim->code_generated_at  = date('Y-m-d H:i:s');
        if ($request->filled('admin_remark')) {
            $claim->admin_remark = $request->admin_remark;
        }
        $claim->save();

        #====Tell the carpenter the product is waiting at the dealer=========#
        SendMessage::getSendMessage('Redemption Processing', $claim->user_id, $claim->points_spent, 'user', ['claim' => $claim]);

        return response()->json([
            'status'      => 'success',
            'msgType'     => 'success',
            'success'     => true,
            'slideToTop'  => true,
            'success_msg' => 'Claim marked as sent to '.$claim->dealer_name.'.',
            'url'         => route('admin.reward-claims.dispatched.list'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Dispatched -> Delivered - no admin action here. Only the dealer's
    | WhatsApp bot can make this transition, by verifying the carpenter's
    | pickup code (see WhatsappBotService::handleCode() -> RewardClaim::markDelivered()).
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Reject - refunds the points back to the carpenter
    |--------------------------------------------------------------------------
    */

    public function rejectModal($id)
    {
        $claim = RewardClaim::find($id);
        $error = empty($claim) ? 'Data Not Found' : null;
        $confirm_route = route('admin.reward-claims.reject', ['id' => $id]);

        $allowPointRefund = WebsiteSetting::where('id', 1)->first()->refund_points_redeem_cancelled;

        return View('admin.reward-claims.reject_modal', compact('error', 'claim', 'confirm_route', 'allowPointRefund'));
    }

    public function reject(Request $request, $id)
    {
        $claim = RewardClaim::find($id);
        # Approved is no longer rejectable from here - the pickup code is
        # already generated and may already be with the carpenter.
        if (empty($claim) || $claim->status != 'Pending') {
            return response()->json([
                'status'     => 'error',
                'error_msg'  => 'This is not a valid action.',
                'slideToTop' => true,
            ]);
        }

        $validator = Validator::make($request->all(), ['admin_remark' => 'required|max:1000']);
        if ($validator->fails()) {
            return response()->json([
                'errorArray' => $validator->errors(),
                'error_msg'  => 'Oops ! Please add a reason for rejection.',
                'slideToTop' => true,
            ]);
        }

        $claim->status       = 'Rejected';
        $claim->admin_remark = $request->admin_remark;
        $claim->save();

        #====Put the stock back when the product tracks it=============#
        if ($claim->reward_product_id) {
            $product = RewardProduct::find($claim->reward_product_id);
            if ($product && $product->stock !== null) {
                RewardProduct::where('id', $product->id)->increment('stock', max(1, (int) $claim->quantity));
            }
        }

        #====Refund the points, same rule the cash flow used===========#
        $allowPointRefund = WebsiteSetting::where('id', 1)->first()->refund_points_redeem_cancelled;

        if (isset($allowPointRefund) && $allowPointRefund == 'Yes') {
            #====Mark the original deduction as cancelled on the ledger====#
            if ($claim->customer_points_id) {
                CustomerPoints::where('id', $claim->customer_points_id)
                    ->update(array('reward_status' => 'cancelled'));
            }

            $pointBalance  = CustomerPoints::getUserBalance($claim->user_id);
            $currentPoints = $pointBalance['balance'] + $claim->points_spent;

            DB::table('customer_points')->insert(
                array(
                    'point'            => $claim->points_spent,
                    'qr_value'         => null,
                    'product_id'       => null,
                    'user_id'          => $claim->user_id,
                    'transaction_type' => 'Earn',
                    'current_points'   => $currentPoints,
                    'added_from'       => 'admin',
                    'description'      => 'Product Claim Rejected - '.$claim->product_name,
                    'redeem_req_id'    => $claim->customer_points_id,
                )
            );
        }

        SendMessage::getSendMessage('Redemption Cancelled', $claim->user_id, $claim->points_spent, 'user', ['claim' => $claim]);

        return response()->json([
            'status'      => 'success',
            'msgType'     => 'success',
            'success'     => true,
            'slideToTop'  => true,
            'success_msg' => 'Claim rejected successfully.',
            'url'         => route('admin.reward-claims.rejected.list'),
        ]);
    }
}
