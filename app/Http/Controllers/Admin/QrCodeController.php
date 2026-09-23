<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Products;
use App\Category;
use DB;

class QrCodeController extends CodespurController
{
    public function index(Request $request)
    {
    	$PARENT_ID=28;

        
        $productdata = Products::leftjoin('category','category.id','products.category_id')->select('products.*','products.created_at',
        DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M %y')) as add_date"),'category.category_name');
    $productdata =  $productdata->where('products.status','Active');
    $productdata =  $productdata->orderBy('id','DESC');

    if (!empty($request->qr_value)) {
        $productdata = $productdata->where('products.qr_value', $request->qr_value);
    }
    if (!empty($request->reward_points)) {
        $productdata = $productdata->where('products.reward_points', $request->reward_points);
    }
    if (!empty($request->product_group_code)) {
        $productdata = $productdata->where('category.id', $request->product_group_code);
    }
    if (!empty($request->used_status)) {
        $productdata = $productdata->where('products.used_status', $request->used_status);
    }
    $productdata = $productdata->paginate(15);   
        $category = Category::get();
        return view('admin.qrcodes.qrcode', compact('PARENT_ID','category', 'productdata'));
    }

}
