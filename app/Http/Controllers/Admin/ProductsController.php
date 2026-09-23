<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Products;
use App\Helpers\Thumbnail;
use App\Category;
use App\Exports\UsersExport;
use App\ProductsBranches;
use App\BranchStores;
use App\ProductImages;
use App\ProductAttributes;
use App\SellerDetails;
use App\SendMessage;
use App\User;
use App\History;
use App\Process;
use App\Jobs\QrCodeGeneratorJobs;
use Redirect;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Session;
use View;
use DB;
use Datatables;
use Illuminate\Support\Facades\Input;
use Response;
use Validator;
use Illuminate\Support\Str;
use Storage;
use ZipArchive;
use File;
use App\Helpers\datehelper;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use App\Import\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;



class ProductsController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {
    	$this->date=datehelper::dateformat();
        ini_set('memory_limit', '-1');
    } 
    

    public function index()
    {	
    	$PARENT_ID=28;
		$category = Category::get();
		$data = Products::leftjoin('category','category.id','products.category_id')->select('products.*','products.created_at',
    		DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M %y')) as add_date"),'category.category_name');
    	$data =  $data->where('products.status','Active');

    	if (!empty($request->qr_value)) {
    		$data = $data->where('products.qr_value', $request->qr_value);
    	}

    	if (!empty($request->reward_points)) {
    		$data = $data->where('products.reward_points', $request->reward_points);
    	}

    	if (!empty($request->product_group_code)) {
    		$data = $data->where('category.id', $request->product_group_code);
    	}
    	$data = $data->get();
    	return view('admin.products.list',compact('PARENT_ID','category','data'));
    }

	//=============== All History Bulk QR ==========================//
	public function history_bulk_qr()
    {	
    	$PARENT_ID=28;
		$data = History::select('qrcode_history.*','process.status as process_status','qrcode_history.created_at',
		DB::raw("(DATE_FORMAT(`tbl_qrcode_history`.created_at,'%d %M %y')) as add_date"),'qrcode_history.qr_code_id','qrcode_history.id','qrcode_history.ip')
		->leftjoin('process','process.history_id','qrcode_history.id')
		->orderBy('id','DESC')->get();
		foreach ($data as $key => $value) 
		{
			$check = array();
			$check_value = array();
			$count = explode(',',$value->qr_code_id);
			// foreach ($count as $keys => $values) {
			// 	$check_value[] = Products::select('used_status')->where('id',$values)->first()->toArray();
			// }
			// foreach ($check_value as $key1 => $value1) {
			// 	$check[] = $value1['used_status'];
			// }
			// 	$validate = in_array($status,$check);
			// 	if($validate == true)
			// 	{
			// 		$value->check_type_value = "Yes";
			// 	}
			// 	else
			// 	{
			// 		$value->check_type_value = "No";
			// 	}
			$value->type = count($count);
			$value->by = User::where('user_type','admin')->where('Status','Active')->first()->full_name;
			
		}
		// echo "<pre>"; print_r($data); die;
    	return view('admin.products.history_bulk_qrcode',compact('PARENT_ID','data'));
    }

	public function history_bulk_download(Request $request,$id)
	{
		$data1 = History::where('id',$id)->first();
		if (empty($data1) || empty($data1->qr_code_id)) {
			return Redirect::route('admin.products.history_bulk_qr')->with(['message' => 'This batch has no QR codes to download.', 'alert-type' => 'error']);
		}

			$qrData = explode(',', $data1->qr_code_id);
			$store = time(). '.' .'qr_code';
			Storage::deleteDirectory('app/uploads/'); //delete the file from the storage
			Storage::disk('local')->makeDirectory('app/uploads/'.$store);

			$productData = Products::select('qr_value','folder_name','size')->whereIn('id',$qrData)->get();

			foreach($productData as $productkey => $productval)
			{
				$this->ensureQrImage($productval);
				Storage::copy('uploads/'.$productval->folder_name.'/'.$productval->qr_value.'.png', 'app/uploads/'.$store.'/'.$productval->qr_value.'.png');
			}

			// ***** Zip download  *****
			$file_path = storage_path('app/app/uploads/'.$store);
			$file_name = "qr_code_".$id."_".date("Y-m-d").".zip";
			$zip = new ZipArchive;
			if ($zip->open(storage_path('app/app/uploads/'.$store.'/'.$file_name), ZipArchive::CREATE) === TRUE)
			{
				$files=File::files(storage_path('app/app/uploads/'.$store));
				if(count($files)>0){
					foreach($files as $key=> $value)
					{
						$relativeNameInZipFile = basename($value);
						$zip->addFile($value, $relativeNameInZipFile);
					}
					$zip->close();
				}
			}
			return response()->download(storage_path('app/app/uploads/'.$store.'/'.$file_name)); 
			// ***** Zip download end here  *****
		
	}

	// ***** Export Excel  *****
	public function exportExcel(Request $request,$id)
	{
		// $data1 = History::where('id',$id)->first();
		// $get_id = explode(',',$data1->qr_code_id);
		// $data = Products::select('id','reward_points','qr_value',DB::raw("(DATE_FORMAT(created_at,'%d %M %y')) as add_date"))->whereIn('id',$get_id)->get()->toArray();
		// $history_bulk[] = array('Sr No.','Reward Value','QR Value','QR Link','Date');
		// foreach($data as $key => $history){
		// 	$value = $key + 1;
		// 	$history_bulk[] = array(
		// 		'Sr No.' => $value,
		// 		'Reward Value' => $history['reward_points'],
		// 		'QR Value' => $history['qr_value'],
		// 		'QR Link' => route('products.download.images',['name' => $history['qr_value']]),
		// 		'Date' => $history['add_date']
		// 	);
		// }
		// $data1 = Products::all();
		// $exports = new UsersExport();
		// $exports->collection($id);
		return Excel::download(new UsersExport($id),"bulk_history_data_".$id."_".date('d-m-Y').".xlsx");

		// Excel::store('bulk_history_data_'.$id.'_'.date('d-m-Y'), function($excel) use($history_bulk) 
		// {
		// 	$excel->setTitle('QR Code Data');
		// 	$excel->sheet('QR Code Data', function($sheet) use($history_bulk) 
		// 	{
		// 		$sheet->fromArray($history_bulk,null,'A1',false,false);
		// 		$sheet->setWidth(array(
		// 			'A'     =>  5,
		// 			'B'     =>  12,
		// 			'C'     =>  25,
		// 			'D'     =>  60,
		// 			'E'     =>  20,
		// 		));
		// 	})->download('xlsx');
		// });
	}
    // ***** Export Excel end here  *****
	

    public function product_image($id)
    {
    	$product_id = $id;
    	$product = Products::find($id);	
    	$PARENT_ID=28;
    	return view('admin.productimg.product-images-list', compact('product','product_id', 'PARENT_ID'));
    }

	//=============== All List Data Function ==========================//
    public function data(Request $request){
    	$data = Products::leftjoin('category','category.id','products.category_id')->select('products.*','products.created_at',
    		DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M %y')) as add_date"),'category.category_name');
    	$data =  $data->where('products.status','Active');

    	if (!empty($request->qr_value)) {
    		$data = $data->where('products.qr_value', $request->qr_value);
    	}

    	if (!empty($request->reward_points)) {
    		$data = $data->where('products.reward_points', $request->reward_points);
    	}

    	if (!empty($request->product_group_code)) {
    		$data = $data->where('category.id', $request->product_group_code);
    	}



    	$data = $data->get();



    	foreach($data as $key=>$value)
    	{
    		$data[$key]->added_date = date('d M y',strtotime($value->created_at));  
    	}
    	return Datatables::of($data)
    	->addColumn('actions', '
@if($used_status == "No")
<a class="delval btn btn-xs btn-primary" title="Edit QR Code" href="{{URL::to("cpmin/products/edit/$id")}}"><i class="fa fa-edit"></i></a>
@endif
<a class="delval btn btn-xs btn-info" title="Download QR Code" href="{{URL::to("download-code/$folder_name/$qr_value")}}"><i class="fa fa-download"></i></a>
<input type="hidden" name="copy_code" id="copy_code" class="copy_code" value="{{URL::to("download-code/$folder_name/$qr_value")}}">
<a class="delval btn btn-xs btn-success copy_link" data_value="{{URL::to("download-code/$folder_name/$qr_value")}}" title="Copy QR Code"><i class="fa fa-copy"></i></a>
@if($used_status == "No")
<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/products/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete QR Code"><i class="fa fa-trash"></i></a>
@endif

    		')
    	->rawColumns(['actions'])
    	->make(true);

            # <a  class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-email" title="Show Product Detail" href="{{URL::to("admin/products/show/$id")}}">
			#<i class="fa fa-eye"></i>
			#</a>
			#<a class="delval btn btn-xs btn-primary" title="Product Images" href="{{URL::to("admin/products/product-images/$id")}}">
			#	<i class="fa fa-image"></i>
			#	</a>
			#	@if(!empty($qrcode_no))
			#   <a href="{{url(App\Helpers\Thumbnail::image("product-qrcodes/".$qrcode_no.".png"))}}" class="delval btn btn-xs btn-info"  title="See QR Code" target="_blank">
			#	<i class="fa fa-money"></i>
			#	QR Code 
			#	</a> @endif

    }
    
    public function  get_seller_cate(){

    	$seller_id =Input::get('seller_id');

    	$seller = SellerDetails::select('category','category.category_name')->where('user_id',$seller_id)
    	->leftjoin('category','category.id','seller_details.category')
    	->first();

    	$output['cate_id']				        = $seller->category;
    	$output['category_name']				= $seller->category_name;

    	return response()->json($output);

    }

    public function create($id=null){

    	$PARENT_ID=28;
    	
    	if($id!=null)
    	{
			$category       = Category::get();
    		$data 			= Products::select('products.*','category.category_name')
			    			 			->leftjoin('category','category.id','products.category_id')->where('products.id',$id)->first();
    		$json_data 		= null;
    		$pro_imgs 		= null;
    	}
    	else
    	{
			$category       = Category::get();
    		$data 			= null;
    		$json_data 		= null;
    		$pro_imgs 		= null;
    	}
    	return view('admin.products.edit',compact('PARENT_ID','data','json_data','pro_imgs','category'));
    }

    public function store(Request $request, $id=null)

    {
    	$rules['reward_points']			= "required|numeric";
    	$rules['qr_size']			= "required|numeric";
    	$rules['category_id']			= "required|numeric";

    	$errorMsg						= "Opps ! Please fill required fields.";
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    		return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
    	}
    	else
    	{
					$year = date('Y');
                    $month = date('m');
                    $week = date('W');
                    $folder = $year.'/'.$month.'/'.$week;
                    Storage::disk('uploads')->makeDirectory($folder);

				$input=$request->all();
				$qrcode = Category::where('id',$input['category_id'])->first()->category_code;
				$qucode_name = strtoupper($qrcode);
				$qrcode_no = $qucode_name.$input['reward_points'].strtoupper(Str::random(10));
				$size = $input['qr_size'];
				if ($id != null) {
					$pro = Products::where('id',$id)->first();
				}
				else{
					$pro = new Products;
					$save_path  = storage_path('app/uploads/'.$folder.'/'.$qrcode_no.'.png');
					$this->generate_qrcode($qrcode_no, $save_path, $size);
					$pro->qr_value = $qrcode_no;
					$pro->qrcode_no = $qrcode_no;
					$pro->size = $size;
					$pro->folder_name = $folder;
				}
					$pro->reward_points = $input['reward_points'];
					$pro->category_id = $input['category_id'];
					$pro->save();
				// $pro = Products::updateOrCreate(['id' => $id], $input);
	
				if($id!=null) 
					$message = "Thank-You! QR Code Information Updated Successfully.";
				else
					$message = "Thank-You! QR Code Created Successfully.";
				$route_value = route('products.download.images',['folder_name' => $pro->folder_name,'name'=>$pro->qr_value]);
				$output['status']			= 'success';
				$output['success_msg']		= $message;
				$output['msg']				= $message;
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['success']			= true;
				$output['slideToTop']		= true;
				$output['hide_class']       = $route_value;
				//$output['url']				= 
				// $output['url']				= route('admin.products.edit',['id'=>$pro->id]);
	
				return response()->json($output);
			// }
    		
    	}
    }

	public function download_image($name){
		$product = Products::where('qr_value',$name)->first();
		if (empty($product)) {
			abort(404);
		}
        return response()->download($this->ensureQrImage($product));
	}
	public function view_image($name){
		$product = Products::where('qr_value',$name)->first();
		if (empty($product)) {
			abort(404);
		}
        return response()->file($this->ensureQrImage($product));
	}

	/**
	 * Path of a QR code's PNG, drawing it again when the file is missing (e.g.
	 * a database copied without its uploads). The image only encodes qr_value,
	 * so a regenerated code scans exactly like the original.
	 */
	private function ensureQrImage($product)
	{
		$folder = $product->folder_name ?: date('Y').'/'.date('m').'/'.date('W');
		$path   = storage_path('app/uploads/'.$folder.'/'.$product->qr_value.'.png');
		if (!is_file($path)) {
			Storage::disk('uploads')->makeDirectory($folder);
			$this->generate_qrcode($product->qr_value, $path, $product->size ?: 300);
		}
		return $path;
	}

    public function import($id=null){
    	$PARENT_ID=28;
    	$route = route('admin.products.saveimport');

    	return view('admin.products.import',compact('PARENT_ID','data','json_data','pro_imgs','sellers'));
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
    	if (!file_exists($filename) || !is_readable($filename))
    		return false;

    	$header = null;
    	$data = array();
    	if (($handle = fopen($filename, 'r')) !== false)
    	{
    		while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
    		{
    			if (!$header)
    				$header = $row;
    			else
    				$data[] = array_combine($header, $row);
    		}
    		fclose($handle);
    	}

    	return $data;
    }

    public function saveimport(Request $request, $id=null)
    {
    	$rules['product_csv'] = "required|mimes:csv,txt,xls,xlsx";

    	$errorMsg = "Opps ! Please fill required fields.";
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    		return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
    	}
    	else{

    		$input=$request->all();
    		$file=$request->product_csv;

    		if (!empty($file))
    		{	
    			Excel::selectSheetsByIndex(0)->load($file, function($reader) {

    				$results = $reader->all();
    				// echo "<pre>";print_r($results);die;
    				foreach($results as $key=>$val)
    				{	

    					$values = array(
    						'qr_value' => $val->qr_value,
    						'reward_points' => $val->reward_points,
    						'product_group_code' => $val->product_group_code,
    						'density' => $val->density,
    						'length' => $val->length,
    						'width' => $val->width,
    						'thickness' => $val->thickness,	
    						'varient' => $val->varient,
    						'sale_date' => date('Y-m-d',strtotime($val->sale_date))
    					);
						#=========If QR Value Already Exists Then Update the Records===========#
    					$count = Products::where('qr_value',$val->qr_value)->count();

    					if($count > 0)
    					{
    						DB::table('products')->where('qr_value', $val->qr_value)->limit(1)->update($values); 
    					}
    					else
    					{
    						DB::table('products')->insert($values);
    					}
    				}

    			});





    		}
    		else 
    		{
    			$message = 'Something wrong, Please check again';
    		} 



    		$message = "Thank-You! Product product_csv Uploaded Successfully.";


    		$output['status']			= 'success';
    		$output['success_msg']		= $message;
    		$output['msg']				= $message;
    		$output['msgHead']			= "Success ! ";
    		$output['msgType']			= "success";
    		$output['success']			= true;
    		$output['slideToTop']		= true;
    		$output['url']				= route('admin.qrcodes');

    		return response()->json($output);
    	}
    }










    public function storePriceVar(Request $request, $id=null)
    {
		//echo "done";
    	$rules['attr_id']		= "required";
    	$rules['attr_value'] 	= "required";
    	$rules['extra_price']	= "required";
    	$rules['display_order']	= "required";

    	$errorMsg				= "Opps ! Please fill required fields.";
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    		return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
    	}
    	else{

    		$input=$request->all();

    		$pro = ProductAttributes::updateOrCreate(['id' => $id], $input);

    		if($id!=null)
    			$message = "Thank-You! Price Variation Updates Successfully.";
    		else
    			$message = "Thank-You! Price Variation Added Successfully.";

    		$output['status']			= 'success';
    		$output['msg']				= $message;
    		$output['msgHead']			= "Success ! ";
    		$output['msgType']			= "success";
    		$output['success']			= true;
    		$output['slideToTop']		= true;
    		$output['resetform']		= true;
    		$output['url']				= route('admin.products.edit',$input['product_id']).'?tab=attrTable';
    		$output['reloadById']		= 'attrTable';

    		return response()->json($output);
    	}
    }

    public function storeImages(Request $request, $id)
    {
		//echo "done"; die;
    	$rules['product_images']		= "required";

    	$errorMsg				= "Opps ! Please fill required fields.";
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
    		return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
    	}
    	else{
    		$input=$request->all();
    		$file = $request->product_images;
			//$records= Marketplace::Select('item_image')->where('id',$id)->first();   	

    		if(!empty($input['product_images'][0]))
    		{
					//$images = array();
    			foreach($file as $key=>$file){		
    				$extension = $file->extension();
    				$folderName = '/products/'.$id;
    				$safeName = str_random(10) . '.' . $extension;
    				@mkdir($folderName,0777,true);
    				@chmod($folderName,0777);
    				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
						//$images[]=$safeName;

    				$img = new ProductImages();
    				$img->image = $safeName;
    				$img->product_id = $id;
    				$img->save();
    			}				
    		}

    		$message = "Thank-You! Image Added Successfully.";

    		$output['status']			= 'success';
    		$output['msg']				= $message;
    		$output['msgHead']			= "Success ! ";
    		$output['msgType']			= "success";
    		$output['success']			= true;
    		$output['slideToTop']		= true;
    		$output['resetform']		= true;
    		$output['selfReload']		= true;

    		return response()->json($output);
    	}
    }

    public function delete_image($id)
    {
		//echo "done"; die;
    	$image = ProductImages::find($id);

    	$folderName = '/products/'.$image->product_id;
    	$filedir = $folderName .'/'. $image->image;
    	Storage::disk('uploads')->delete($filedir);

    	$image->forceDelete();
    	return response()->json(['status'=>'success']);
    }

    public function priVarData(){

    	$data = ProductAttributes::select('*',DB::raw("(DATE_FORMAT(`tbl_product_attributes`.created_at,'%d %M  %Y')) as add_date"))->get();

    	return Datatables::of($data)
    	->addColumn('actions', '
    		<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/products/$id/confirm-delete-Pri-var")}}" class="delval btn btn-xs btn-danger"  title="Delete Store Item">
    		<i class="fa fa-trash"></i>
    		Delete
    		</a>

    		')
    	->rawColumns(['actions'])
    	->make(true);

    }

    public function getModalDeletePriVar($id = null)
    {

    	$model = 'Price Variation';
    	$confirm_route = $error = null;
    	$store= ProductAttributes::where('id', $id)->first();
    	if (empty($store)) {
    		return Redirect::route('info');
    	}
    	else
    	{
    		$confirm_route = route('delete/price-variation', ['id' => $id]);
    		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    	}
    }




    public function destroyPriVar($id)
    {
    	$store = ProductAttributes::find($id);

    	$res=$store->delete();

    	$message="Success! Attribute Deleted Successfully";
    	$notification = array(

    		'message' =>  $message, 
    		'alert-type' => 'success'
    	);

    	return redirect('admin/products/edit/'.$store->product_id.'?tab=attrTable')->with($notification);

    }


    public function view($id){
    	$PARENT_ID=28;
    	$detail	= Products::select('*',DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M  %Y')) as add_date"),DB::raw("(DATE_FORMAT(`tbl_products`.updated_at,'%d %M  %Y')) as update_date"))->find($id);

    	if(count((array)$detail)==0)
    	{
    		$notification = array(
    			'message' =>  'Sorry Tutorials Not Found.', 
    			'alert-type' => 'warning'
    		);
    		return redirect('admin/products')->with($notification);
    	}
    	return view('admin.products.view',compact('PARENT_ID','detail'));
    }

    public function getModalDelete($id = null)
    {

    	$model = 'Product';
    	$confirm_route = $error = null;
    	$store= Products::where('id', $id)->first();
    	if (empty($store)) {
    		return Redirect::route('info');
    	}
    	else
    	{
    		$confirm_route = route('delete/products', ['id' => $id]);
    		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    	}
    }

	public function getModalDeleteBulk($id = null)
    {

    	$model = 'Bulk Qr Code';
    	$confirm_route = $error = null;
    	$store= History::where('id', $id)->first();
    	if (empty($store)) {
    		return Redirect::route('info');
    	}
    	else
    	{
    		$confirm_route = route('delete/bulk_history', ['id' => $id]);
    		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    	}
    }


	public function delete_bulk_history($id)
    {
		//$store = Products::find($id);
    	$check= History::where('id', $id)->first();
		$qr_code_id = explode(',',$check->qr_code_id);
		$unused_data = Products::whereIn('id',$qr_code_id)->where('used_status','No')->delete();
    	$store= History::where('id', $id)->delete();
    	$message="Success! History Deleted Successfully";
    	$notification = array(

    		'message' =>  $message, 
    		'alert-type' => 'success'
    	);
    	return redirect('cpmin/products/history-bulk')->with($notification);

    }

    public function destroy($id)
    {
		//$store = Products::find($id);

    	$input['status'] = 'Inactive';	
    	$pro = Products::updateOrCreate(['id' => $id], $input);
		//$res=$store->delete();
    	$message="Success! Product Item Deleted Successfully";
    	$notification = array(

    		'message' =>  $message, 
    		'alert-type' => 'success'
    	);
    	return redirect('cpmin/qrcodes')->with($notification);

    }

   //========= Add Files ================//


    public function mainsubCatData()
    {
    	$cat = Input::get('cat');
    	$subCat = Category::select('id','category_name')->where('parent_menu',$cat)->orderBy('category_name','asc')->get();	
    	$output['subCat'] = $subCat;
    	return response()->json($output);
    }

    public function subCatData()
    {
    	$cat = Input::get('sub_cat');
    	$subpartCat = Category::select('id','category_name')->where('parent_menu',$cat)->orderBy('category_name','asc')->get();	
		//echo "<pre>"; print_r($subCat); die;
    	$output['subpartCat'] = $subpartCat;
    	return response()->json($output);
    }


	//========================================products TRASH====================================================

    public function listDeletedtrash()
    { 
    	$request_url_user = 'QR Trash';
    	$PARENT_ID = 51;
		// $d = Products::get()->toArray();
		// echo "<pre>";
		// print_r($d);
    	return view('admin.products.deletedlist',compact('PARENT_ID','request_url_user'));
    }
    public function listDeletedData(){
    	$enquiry = Products::leftjoin('category','category.id','products.category_id')->select('*','products.created_at',
    		DB::raw("(DATE_FORMAT(`tbl_products`.created_at,'%d %M %y')) as add_date"),'category.category_name')
			->where('status','Inactive')
    	->get();

    	return Datatables::of($enquiry)
    	->addColumn('actions','<div class="btn-group">
    		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("cpmin/products_trash/$id/confirm-restore")}}" class="btn btn-small btn-info"title="Restore"><i class="fa fa-undo"> Restore</i>
    		</a>
    		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("cpmin/products_trash/$id/final-delete")}}" class="btn btn-small btn-danger"title="Restore"><i class="fa fa-trash"> Deelete</i>
    		</a>
    		</div>
    		')->rawColumns(['actions'])->make(true);


    }


    public function getModalRestore($id = null)
    {
    	$model = 'QR Trash Record';
    	$confirm_route = $error = null;
    	$confirm_route = route('restore/products_trash', ['id' => $id]);
    	return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
    public function restoreDeletedtrash($enu_id)
    {
    	Products::withTrashed()->where('id',$enu_id)->restore();
		// update record status Inactive
    	Products::where('id', $enu_id)->first()->update(['status' => 'Active']);
    	$success="Trash Record Restore Successfully";
    	return Redirect::route('products_trash/qr-trash')->with('success', $success);
    }

    public function getModalFinalDelete($id = null)
    {
    	$model = 'Products Trash Record';
    	$confirm_route = $error = null;
    	$confirm_route = route('finaldelete/products_trash', ['id' => $id]);
    	return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
    public function permanentDelete($id)
    {
       //record permanent delete where status is Inactive or that  are trashed
    	$detail=Products::where('id',$id)->onlyTrashed()->first();
		// if(empty($detail))
		//   $detail = Products::where('id',$id)->where('status', 'Inactive')->first();
    	if(isset($detail->product_image))
    	{
    		$folderName = '/products';
    		$filedir = $folderName.'/'.$detail->product_image;
    		$query=Storage::disk('uploads')->delete($filedir);
    	}

    	Products::where('id',$id)->withTrashed()->forceDelete();
    	$success="Product Permanently Deleted Successfully";
    	$notification = array(
    		'message' =>  $success, 
    		'alert-type' => 'success'
    	);
    	return Redirect::route('products_trash/qr-trash')->with($notification);
    }


    /*generate qrcode*/
    private function generate_qrcode($data, $save_path, $size)
    {
    	$result = Builder::create()
    	->writer(new PngWriter())
    	->writerOptions([])
    	->data($data)
    	->encoding(new Encoding('UTF-8'))
    	->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    	->size($size)
    	->margin(10)
    	->roundBlockSizeMode(new RoundBlockSizeModeMargin())
    	->build();
		// Save it to a file
    	$result->saveToFile($save_path);
    }
	public function bulk_create(Request $request, $id=null){
		$PARENT_ID=28;
    	if($id!=null)
    	{
			$data = History::select('qrcode_history.*','qrcode_history.created_at',
		DB::raw("(DATE_FORMAT(`tbl_qrcode_history`.created_at,'%d %M %y')) as add_date"),'qrcode_history.qr_code_id','qrcode_history.id','qrcode_history.ip')->where('qrcode_history.id',$id)->first();

					$category       = Category::get();
					$data 			= History::select('qrcode_history.*','category.category_name')
												->leftjoin('category','category.id','qrcode_history.category_id')->where('qrcode_history.id',$id)->first();
					$json_data 		= null;
					$pro_imgs 		= null;
					$edit_data = 5;
					return view('admin.products.bulk_qrcode',compact('PARENT_ID','data','json_data','pro_imgs','category','edit_data'));
    	}
    	else
    	{
			$category       = Category::get();
    		$data 			= null;
    		$json_data 		= null;
    		$pro_imgs 		= null;
			$edit_data = 4;
			return view('admin.products.bulk_qrcode',compact('PARENT_ID','data','json_data','pro_imgs','category','edit_data'));

    	}
		//echo $json_data; die;
	}
	public function bulk_store(Request $request, $id=null){
		$id = $request->id;
		$update_data_id = $request->id;
		$bulk_qr_code = (int)$request->bulk_qr_code;
		if ($id != null) {
			$rules['reward_points']			= "required|numeric";
		}
		else {
			$rules['bulk_qr_code']          = "required|numeric";
				$rules['qr_size']			= "required";
				$rules['category_id']			= "required";
		}	
				
			$errorMsg						= "Opps ! Please fill required fields.";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
			}
			else
			{
					$input=$request->all();
					if ($id != null) {
						$type = "UpdateQrCode";
						$history = History::where('id',$id)->first();
						$history->update_data_id = $update_data_id;
						$history->remark = $input['remark'];
						$history->reward_points = $input['reward_points'];
						$message = "Thank-You! QR Code Created Successfully.";
					}
					else{
						$type = "GenerateQrCode";
						$history = new History;
						$history->admin_id = Sentinel::check()->id;
						$history->qr_size = $input['qr_size'];
						$history->reward_points = $input['reward_points'];
						$history->bulk_qr_code = $input['bulk_qr_code'];
						$history->category_id = $input['category_id'];
						$history->remark = $input['remark'];
						$message = "Thank-You! QR Code Created Successfully.";
						
					}
					$history->status = "Processing";
					$history->type = $type;
					$history->save();
					QrCodeGeneratorJobs::dispatch($history);
		
				
				// $route_value = route('products.download.images',['name'=>$pro->qr_value]);
				$output['status']			= 'success';
				$output['success_msg']		= $message;
				$output['msg']				= $message;
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['success']			= true;
				$output['slideToTop']		= true;
				// $output['hide_class']       = $route_value;
				//$output['url']				= 
				if ($id != null) {
					$output['url']				= route('admin.products.history_bulk_qr');
				}
				else {
					$output['url']				= route('admin.products.history_bulk_qr');
				}
	
				return response()->json($output);
			}
		
	}
	public function printqrcode(Request $request, $id){
		$data = History::select('qrcode_history.*','qrcode_history.created_at',
		DB::raw("(DATE_FORMAT(`tbl_qrcode_history`.created_at,'%d %M %y')) as add_date"),'qrcode_history.qr_code_id','qrcode_history.id','qrcode_history.ip')->where('qrcode_history.id',$id)->first();
			$count = explode(',',$data->qr_code_id);
			$codes = Products::whereIn('id',$count)->select('reward_points','qr_value','folder_name')->get()->toArray();
			// dd($codes);
			return view('admin.products.print',compact('codes'));
	}
}