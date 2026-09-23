<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Products;
use App\AboutUs;
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

class AboutUsController extends CodespurController
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

		$PARENT_ID=98;
		return view('admin.about-us.list',compact('PARENT_ID'));
	}


	//=============== All List Data Function ==========================//
	public function data(){
		
		 $data = AboutUs::select('about_us.*', DB::raw("(DATE_FORMAT(`tbl_about_us`.created_at,'%d %M %y')) as create_date"))
				 ->get();
		 
		 return Datatables::of($data)
            ->addColumn('actions', '
                <a  class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-email" title="Show Section Detail" href="{{URL::to("admin/about-us/show/$id")}}">
				<i class="fa fa-eye"></i>
				</a>

            	<a class="delval btn btn-xs btn-primary" title="Edit Section Detail" href="{{URL::to("admin/about-us/edit/$id")}}">
				<i class="fa fa-edit"></i>
				</a>

				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/about-us/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Section">
				<i class="fa fa-trash"></i>
				</a>
				
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}
    
	
	public function create($id=null)
	{
		
		$PARENT_ID=98;
		$data = AboutUs::where('id',$id)->first();
		return view('admin.about-us.edit',compact('PARENT_ID','data'));
	}
	
	public function store(Request $request, $id=null)
	{
		$rules['display_order']			= "numeric";
		$rules['description']	= "required";
        if($request->image)
		  $rules['image']			= "mimes:jpg,jpeg,png";
		
		$errorMsg						= "Opps ! Please fill required fields.";

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		else
		{	
			$input=$request->all();
			if(empty($request->title))
				$input['title'] = 'Not Found';
			
			$file=$request->image;
			$records = AboutUs::Select('image')->where('id',$id)->first();   	
	
			if (!empty($file))
			{	
				$extension = $file->extension();
				$folderName = '/about-us';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['image'] = $safeName;
				
				 if(count($records)>0 && ($records->image!='') && ($input['image']!=''))
				{
					$folderName = '/about-us';
					$filedir = $folderName .'/'. $records->image;
					Storage::disk('uploads')->delete($filedir);
				}
				
			}
			else 
			{
				$input['image'] = isset($records->image) ? $records->image : '';
			} 

			AboutUs::updateOrCreate(['id' => $id], $input);
		
		 
			if($id!=null) 
			  $message = "Section Updated Successfully.";
			else
			  $message = "Section Created Successfully.";
			
			$output['status']			= 'success';
			$output['success_msg']		= $message;
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('admin.about-us');
			
			return response()->json($output);
		}
	}
	
	public function view($id)
	{
		$PARENT_ID=98;
		$detail	=  AboutUs::select('*',DB::raw("(DATE_FORMAT(created_at,'%d %M  %Y')) as create_date"),
									DB::raw("(DATE_FORMAT(updated_at,'%d %M  %Y')) as update_date"))
		                            ->where('id', $id)
									->first();
		
		if($detail == null)
		{
		$notification = array(
			'message' =>  'Sorry ! Section Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/about-us')->with($notification);
		}
		return view('admin.about-us.view',compact('PARENT_ID','detail'));
	}
	
	public function getModalDelete($id = null)
    {
	
		$model = 'Section Delete';
		$confirm_route = $error = null;
		$store= AboutUs::where('id', $id)->first();
		if (empty($store)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/about-us', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		AboutUs::where('id', $id)->first()->forceDelete();
		$message="Success! Section Deleted Successfully";
		$notification = array(
			
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('admin/about-us')->with($notification);
		
	}  

 }
