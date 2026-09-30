<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Thumbnail;
use DB;
use URL;

class RewardProduct extends Eloquent
{
    use SoftDeletes;

    protected $table = 'reward_products';
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'category_id'     => 'integer',
        'points_required' => 'integer',
        'stock'           => 'integer',
        'sort_order'      => 'integer',
        'status'          => 'boolean',
    ];

    function __construct()
    {
        parent::__construct();
        $this->attributes = array('ip' => Thumbnail::getclientip());
    }

    public function category()
    {
        return $this->belongsTo(RewardCatalogCategory::class, 'category_id');
    }

    /************************** Api function Starts ****************************/

    #=>=>=>=>=>=>=>=>Reward product catalog for the App=>=>=>=>=>=>=>=>=>=>=>=>=>#
    public static function rewardProducts($params)
    {
        $result = array();
        $status = 'error';
        $msg    = '';

        try {
            $imagePath = URL::to('uploads/reward-products');

            $query = self::leftjoin('reward_catalog_categories', 'reward_catalog_categories.id', 'reward_products.category_id')
                ->select(
                    'reward_products.id',
                    'reward_products.name',
                    'reward_products.sku',
                    'reward_products.short_description',
                    'reward_products.description',
                    'reward_products.points_required',
                    'reward_products.price',
                    'reward_products.stock',
                    'reward_products.category_id',
                    'reward_catalog_categories.name as category_name',
                    DB::raw("IF(STRCMP(tbl_reward_products.image,''),CONCAT('".$imagePath."/"."',tbl_reward_products.image),'') as image", false)
                )
                ->where('reward_products.status', 1)
                ->whereNull('reward_catalog_categories.deleted_at');

            if (isset($params['category_id']) && $params['category_id'] != null) {
                $query->where('reward_products.category_id', $params['category_id']);
            }

            if (isset($params['keyword']) && $params['keyword'] != '') {
                $keyword = $params['keyword'];
                $query->where(function ($query2) use ($keyword) {
                    $query2->orWhere('reward_products.name', 'like', '%'.$keyword.'%');
                    $query2->orWhere('reward_products.short_description', 'like', '%'.$keyword.'%');
                });
            }

            $products = $query->orderBy('reward_products.sort_order', 'asc')
                ->orderBy('reward_products.points_required', 'asc')
                ->get();

            #====Flag each product against the balance of the logged in user======#
            if (isset($params['user_id']) && $params['user_id'] != null) {
                $balance = @CustomerPoints::select('current_points')
                    ->where('user_id', $params['user_id'])
                    ->orderBy('id', 'desc')->first()->current_points;

                $balance = (int) $balance;

                foreach ($products as $product) {
                    $product->can_redeem = ($balance >= $product->points_required) ? 'Yes' : 'No';
                    $product->points_needed = max(0, $product->points_required - $balance);
                }

                $result['available_points'] = $balance;
            }

            $result['data'] = $products;
            $status = 'success';
            $msg    = 'Reward Products Found Successfully';
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : ".$e->getMessage();
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

    #=>=>=>=>=>=>=>=>Reward product categories for the App=>=>=>=>=>=>=>=>=>=>=>#
    public static function rewardProductCategories($params)
    {
        $result = array();
        $status = 'error';
        $msg    = '';

        try {
            $imagePath = URL::to('uploads/reward-catalog-categories');

            $data = RewardCatalogCategory::select(
                'id',
                'name',
                'slug',
                'description',
                DB::raw("IF(STRCMP(tbl_reward_catalog_categories.image,''),CONCAT('".$imagePath."/"."',tbl_reward_catalog_categories.image),'') as image", false)
            )
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->get();

            $result['data'] = $data;
            $status = 'success';
            $msg    = 'Reward Product Categories Found Successfully';
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
