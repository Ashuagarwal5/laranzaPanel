<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\BranchStores;
use App\BranchReview;
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

class BranchReviewController extends CodespurController
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
		$totalRecord = BranchReview::count();
		return view('admin.branch_reviews.list',compact('PARENT_ID','totalRecord'));
	}
	//=============== All List Data Function ==========================//
	public function data(){
		//echo "done"; die;
		 $data = BranchReview::select('*',DB::raw("(DATE_FORMAT(`tbl_branch_reviews`.created_at,'%d %M  %Y')) as add_date"))->get();
		 
		 foreach($data as $key=>$value)
		 {
			 $bn = BranchStores::select('branch_name')->where('id',$value->branch_id)->first();
			 if($bn->branch_name)
			 $data[$key]->branch_name = $bn->branch_name;
			 else
			 $data[$key]->branch_name = "N/A";
		 }
		 
		 return Datatables::of($data)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/branch_reviews/show/$id")}}"data-toggle="modal" data-target="#modal-email">
				<i class="fa fa-eye"></i>
				
				</a>
				<a class="delval btn btn-xs btn-primary" title="Edit Branch Reviews" href="{{URL::to("admin/branch_reviews/edit/$id")}}">
				<i class="fa fa-edit"></i>
				
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/branch_reviews/$id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Branch Reviews">
				<i class="fa fa-trash"></i>
				
				</a>
              ')
			->rawColumns(['actions'])
            ->make(true);		
	}

	 public function create($id=null){
		$PARENT_ID=56;
	    $data	= BranchReview::find($id);	
		$lng    = 26.9124336;
		$lat    = 75.78727090000007;		
		$data	= BranchReview::find($id);		
		return view('admin.branch_reviews.edit',compact('PARENT_ID','data','lng','lat'));
	}
	
	public function store(Request $request,$id=null)
	{
		if($id!=NULL)
		{
			$this->validate($request,[
			        'user_name'  =>'required',
			        'review'  	 =>'required',
			        'rate'  	 =>'required',
				]);
		}
		else{
			$this->validate($request,[
			        'user_name'  =>'required',			        
			        'review'  	 =>'required',			        
			        'rate'  	 =>'required',			        
				]);
		}
		$input = $request->all();
			
		if($id!=null)
		$message="Branch Reviews Updated Successfully";
		else
		$message="Branch Reviews Created Successfully";
		BranchReview::updateOrCreate(['id' => $id],$input);		
		$notification = array(
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('admin/branch_reviews')->with($notification);
	}
	
	
   	public function view($id){
	    $PARENT_ID=56;
		$detail	= BranchReview::select('*'
		,DB::raw("(DATE_FORMAT(`tbl_branch_reviews`.created_at,'%d %M  %Y')) as add_date")
		,DB::raw("(DATE_FORMAT(`tbl_branch_reviews`.created_at,'%d %M  %Y')) as update_date"))->find($id);
		
		$bn = BranchStores::select('branch_name')->where('id',$detail->branch_id)->first();
		 if($bn->branch_name)
		 $detail->branch_name = $bn->branch_name;
		 else
		 $detail->branch_name = "N/A";
		
		if(count($detail)==0){
		$notification = array(
			'message' =>  'Sorry Branch Stores Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/branch_reviews')->with($notification);
		}
		return view('admin.branch_reviews.view',compact('PARENT_ID','detail'));
	}	

	public function getModalDelete($id = null)
    {	
		$model = 'Branch Reviews';
		$confirm_route = $error = null;
		$branch= BranchReview::where('id', $id)->first();
		if (empty($branch)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/branch_reviews', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }

	public function destroy($id)
	{
		$branch = BranchReview::find($id);
		$res=$branch->delete();
		return Redirect::route('admin.branch_reviews');
	}
 }
