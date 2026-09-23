<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Banner;
use App\User;
use Redirect;
use Sentinel; 
use Session;
use View;
use DB;
use Datatables;
use Illuminate\Support\Facades\Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;
use Illuminate\Support\Str;
use App\CustomerRewards;

class BannerController extends CodespurController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	function __construct()
	{
		$this->date=datehelper::dateformat();
	} 
	public function index()
	 {	

		$PARENT_ID=101;
		
		/*
		
		$currentPointsBalance = 5908;
		$bonusStepCompletd = 0;
$bonusRewardRecord= DB::select('SELECT * FROM `tbl_customer_rewards` WHERE '.$currentPointsBalance.' >= points_from and '.$currentPointsBalance.' <= points_to and level > '.$bonusStepCompletd.';');
		echo "<pre>";	
		print_r($bonusRewardRecord);
		echo $bonusRewardRecord[0]->bonus_ponits;

		die();
		*/
		return view('admin.page-banner.list',compact('PARENT_ID'));
	}


	//=============== All List Data Function ==========================//
	public function data(){
		
		 $data = Banner::select('banners.*', DB::raw("DATE_FORMAT(tbl_banners.created_at, '%d %M %Y') as create_date"))
				 ->get();
		 
		 return Datatables::of($data)
            ->addColumn('actions', '
                

            	<a class="delval btn btn-xs btn-primary" title="Edit  Detail" href="{{URL::to("cpmin/page-banner/edit/$id")}}">
				<i class="fa fa-edit"></i>
				</a>
				<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/page-banner/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
				
              ')
			->rawColumns(['actions'])
            ->make(true);

            /*
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/page-banner/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Section">
				<i class="fa fa-trash"></i>
				</a>
            */
		
	}
    
	
	public function create($id=null)
	{
		
		$PARENT_ID=98;
		$data = Banner::where('id',$id)->first();
		return view('admin.page-banner.edit',compact('PARENT_ID','data'));
	}
	
	public function store(Request $request, $id=null)
	{
		$rules['title']	= "required";
		$rules['description']	= "required";
		//$rules['page_name']	= "required";
		
		if(empty($id))
		{
		   $rules['image']			= "required";
		   $rules['image']			= "mimes:jpg,jpeg,png";
		}
		else
		{
			if($request->image)
		      $rules['image']	    = "mimes:jpg,jpeg,png";
		}

		$errorMsg						= "Opps ! Please fill required fields.";

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		else
		{	
			$input=$request->all();
			
			$file=$request->image;
			$records = Banner::Select('image')->where('id',$id)->first();   	
	
			if (!empty($file))
			{	
				$extension = $file->extension();
				$folderName = '/page-banner';
				$safeName = Str::random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['image'] = $safeName;
				
			}
			else 
			{
				$input['image'] = isset($records->image) ? $records->image : '';
			} 

			Banner::updateOrCreate(['id' => $id], $input);
		
		 
			if($id!=null) 
			  $message = "Banner Updated Successfully.";
			else
			  $message = "Banner Created Successfully.";
			
			$output['status']			= 'success';
			$output['success_msg']		= $message;
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('admin.page-banner');
			
			return response()->json($output);
		}
	}
	
	public function view($id)
	{
		$PARENT_ID=98;
		$detail	=  Banner::select('*',DB::raw("(DATE_FORMAT(created_at,'%d %M  %Y'))"),
									DB::raw("(DATE_FORMAT(updated_at,'%d %M  %Y')) "))
		                            ->where('id', $id)
									->first();
		
		if($detail == null)
		{
		$notification = array(
			'message' =>  'Sorry ! Banner Data Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('cpmin/page-banner')->with($notification);
		}
		return view('admin.page-banner.view',compact('PARENT_ID','detail'));
	}
	
	public function getModalDelete($id = null)
    {
	
		$model = 'Delete Banner';
		$confirm_route = $error = null;
		$store= Banner::where('id', $id)->first();
		if (empty($store)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/page-banner', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		Banner::where('id', $id)->forceDelete();
		$message="Success! Banner Deleted Successfully";
		$notification = array(
			
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('cpmin/page-banner')->with($notification);
		
	}  

 }
