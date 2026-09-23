<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Infobox;
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

class InfoboxController extends CodespurController
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
		// die('working');
		$PARENT_ID=118;
		return view('admin.infobox.list',compact('PARENT_ID'));
	}


	//=============== All List Data Function ==========================//
	public function data(){
		
		 $data = Infobox::select('infobox.*', DB::raw("DATE_FORMAT(tbl_infobox.created_at, '%d %M %Y') as create_date"))
				 ->get();
		//  dd($data);
		 return Datatables::of($data)
            ->addColumn('actions', '
                

            	<a class="delval btn btn-xs btn-primary" title="Edit  Detail" href="{{URL::to("admin/infobox/edit/$id")}}">
				<i class="fa fa-edit"></i>
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/infobox/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Section">
				<i class="fa fa-trash"></i>
				</a>
              ')
			->rawColumns(['actions'])
            ->make(true);

            
				
            
		
	}
    
	
	public function create($id=null)
	{
		
		$PARENT_ID=118;
		$data = Infobox::where('id',$id)->first();
		return view('admin.infobox.edit',compact('PARENT_ID','data'));
	}
	
	public function store(Request $request, $id=null)
	{
		$rules['title']	= "required";
		
		if(empty($id))
		{
		   $rules['file']			= "required|mimes:pdf|max:10000";
		   $rules['image']			= "required";
		   $rules['image']			= "mimes:jpg,jpeg,png";
		}
		else
		{
			if($request->file)
		      $rules['file']	    = "mimes:pdf|max:10000";
			 
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
			
			$file=$request->file;
			$records = Infobox::Select('file')->where('id',$id)->first();   	
	
			if (!empty($file))
			{	
				$extension = $file->extension();
				$folderName = '/infobox';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['file'] = $safeName;
				
				 if(count($records)>0 && ($records->file!='') && ($input['file']!=''))
				{
					$folderName = '/infobox';
					$filedir = $folderName .'/'. $records->file;
					Storage::disk('uploads')->delete($filedir);
				}
				
			}
			else 
			{
				$input['file'] = isset($records->file) ? $records->file : '';
			} 

			$image=$request->image;
			$records = Infobox::Select('image')->where('id',$id)->first();   	
	
			if (!empty($image))
			{	
				$extension = $image->extension();
				$folderName = '/infobox';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $image,$safeName);
				$input['image'] = $safeName;
				
				 if(count($records)>0 && ($records->image!='') && ($input['image']!=''))
				{
					$folderName = '/infobox';
					$filedir = $folderName .'/'. $records->image;
					Storage::disk('uploads')->delete($filedir);
				}
				
			}
			else 
			{
				$input['image'] = isset($records->image) ? $records->image : '';
			} 











			Infobox::updateOrCreate(['id' => $id], $input);
		
		 
			if($id!=null) 
			  $message = "infobox Updated Successfully.";
			else
			  $message = "infobox Created Successfully.";
			
			$output['status']			= 'success';
			$output['success_msg']		= $message;
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('admin.infobox');
			
			return response()->json($output);
		}
	}
	
	public function view($id)
	{
		$PARENT_ID=98;
		$detail	=  Infobox::select('*',DB::raw("(DATE_FORMAT(created_at,'%d %M  %Y'))"),
									DB::raw("(DATE_FORMAT(updated_at,'%d %M  %Y')) "))
		                            ->where('id', $id)
									->first();
		
		if($detail == null)
		{
		$notification = array(
			'message' =>  'Sorry ! infobox Data Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/infobox')->with($notification);
		}
		return view('admin.infobox.view',compact('PARENT_ID','detail'));
	}
	
	public function getModalDelete($id = null)
    {
	
		$model = 'Delete infobox';
		$confirm_route = $error = null;
		$store= Infobox::where('id', $id)->first();
		if (empty($store)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/infobox', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		Infobox::where('id', $id)->first()->forceDelete();
		$message="Success! infobox Deleted Successfully";
		$notification = array(
			
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('admin/infobox')->with($notification);
		
	}  

 }
