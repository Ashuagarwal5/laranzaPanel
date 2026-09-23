<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\BranchStores;
use Redirect;
use Sentinel;
use Session;
use View;
use DB;
use File;
use Datatables;
use Input;
use Response;
use Validator;
use App\Helpers\datehelper;
use Illuminate\Support\Facades\Storage;

class BranchStoresController extends CodespurController
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
		$PARENT_ID=56;
		$totalRecord = BranchStores::count();
		return view('admin.branch_stores.list',compact('PARENT_ID','totalRecord'));
	}
	//=============== All List Data Function ==========================//
	public function data(){
		//echo "done"; die;
		 $data = BranchStores::select('*',DB::raw("(DATE_FORMAT(`tbl_branch_stores`.created_at,'%d %M  %Y')) as add_date"))->get();
		 return Datatables::of($data)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/branch_stores/show/$id")}}"data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				View
				</a>
				<a class="delval btn btn-xs btn-primary" title="Edit Branch Stores" href="{{URL::to("admin/branch_stores/edit/$id")}}">
				<i class="fa fa-edit"></i>
				Edit
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/branch_stores/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Branch Stores">
				<i class="fa fa-trash"></i>
				Delete
				</a>
              ')
			->rawColumns(['actions'])
            ->make(true);
		
	}
	
	  //~ public function create($id=null){
		  //~ echo "done"; die;		
		//~ $data	= BranchStores::find($id);
		//~ return view('admin.branch_stores.edit',compact('data'));
	//~ }
	
	 public function create($id=null){
		$PARENT_ID=56;
	    $data	= BranchStores::find($id);	
		$lng    = 26.9124336;
		$lat    = 75.78727090000007;		
		$data	= BranchStores::find($id);		
		return view('admin.branch_stores.edit',compact('PARENT_ID','data','lng','lat'));
	}
	
	public function store(Request $request,$id=null)
	{
		if($id!=NULL)
		{
			$this->validate($request,[
			        'branch_name'  =>'required',
			        'state'  	   =>'required',
			        'city'  	   =>'required',
			        'country'  	   =>'required',
			        'address'  	   =>'required',
			        'longitude'  	   =>'required',
			        'latitude'  	   =>'required',
					'branch_image' => 'mimes:jpg,png,jpeg',
				]);
		}
		else{
			$this->validate($request,[
			        'branch_name'  =>'required',
			        'state'  	   =>'required',
			        'city'  	   =>'required',
			        'country'  	   =>'required',
			        'address'  	   =>'required',
			        'longitude'  	   =>'required',
			        'latitude'  	   =>'required',
					'branch_image' => 'mimes:jpg,png,jpeg',
				]);
		}
			
			$file=$request->branch_image;
			$input = $request->all();
			$records= BranchStores::Select('branch_image')->where('id',$id)->first();   	
	
			if (!empty($file))
			{				
				$extension = $file->extension();
				$folderName = '/branch';
				$safeName = str_random(10) . '.' . $extension;
				@mkdir($folderName,0777,true);
				@chmod($folderName,0777);
				Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
				$input['branch_image'] = $safeName;
				
				 if(count($records)>0 && ($records->branch_image!='') && ($input['branch_image']!=''))
				{
					$folderName = '/branch';
					$filedir = $folderName .'/'. $records->branch_image;
					Storage::disk('uploads')->delete($filedir);
				}				
			}
			else
			{
				$input['branch_image'] = $records['branch_image'];
			}
			
		if($id!=null)
		$message="Branch Stores Updated Successfully";
		else
		$message="Branch Stores Created Successfully";
		BranchStores::updateOrCreate(['id' => $id], $input);
		
		$notification = array(
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('admin/branch_stores')->with($notification);
	}
	
	
   	public function view($id){
	    $PARENT_ID=56;
		$detail	= BranchStores::select('*'
		,DB::raw("(DATE_FORMAT(`tbl_branch_stores`.created_at,'%d %M  %Y')) as add_date")
		,DB::raw("(DATE_FORMAT(`tbl_branch_reviews`.created_at,'%d %M  %Y')) as update_date"))->find($id);
		if(count($detail)==0){
		$notification = array(
			'message' =>  'Sorry Branch Stores Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/branch_stores')->with($notification);
		}
		return view('admin.branch_stores.view',compact('PARENT_ID','detail'));
	}	

	public function getModalDelete($id = null)
    {	
		$model = 'Branch Stores';
		$confirm_route = $error = null;
		$branch= BranchStores::where('id', $id)->first();
		if (empty($branch)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/branch_stores', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		$branch = BranchStores::find($id);
		$res=$branch->delete();
		return Redirect::route('admin.branch_stores ');
	}
 }
