<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\UserReview as app_modal;
use App\Answer;
use Validator;
use DB;
use App\WebsiteSetting;
use Datatables;
use Redirect;
use App\Helpers\datehelper;

class UserReviewController extends Controller{

    function __construct()
	{
		//  $this->date=datehelper::dateformat();
		
		 $this->manager_name  = 'User Review';
		 $this->folder_name   = 'user-review';
		// app_modal     = app_modal;
		 $this->manager_url   = route('admin.user-review');
		 $this->store_url     = route('admin.user-review.store');
		//  $this->date_format   = WebsiteSetting::select('dateformat')->first();

	}

	//================== Admin   View Function ==================//
	public function index()
	{
		$manager_name = $this->manager_name;
		$PARENT_ID=105;
		$route_create = route('user-review.edit');
		return view('admin.'.$this->folder_name.'.list',compact('PARENT_ID','manager_name','route_create'));
		
	}

	 public function data()
    {
        $data =  app_modal::select(['users_reviews.*',  'users.first_name',
        					'products.product_title',
        					 DB::raw("DATE_FORMAT(tbl_users_reviews.created_at,'%d, %M, %Y') as add_date")] )
        					->join('products', 'products.id', 'users_reviews.product_id')
        					->join('users', 'users.id', 'users_reviews.user_id')
           					->orderBy('users_reviews.id', 'desc')
           					->get();

		 return Datatables::of($data)
		 ->addColumn('actions', '<div class="btn-group">
				<a title="View Record" class="btn btn-success" href="{{route("admin.show-review", $id)}}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>

			   <a class="btn btn-primary" href="{{route("user-review.edit", $id)}}" title="Edit Record"><i class="fa fa-edit"></i> </a>

			   <a data-original-title="Delete Record"  class="btn btn-danger enable-tooltip" href="{{route("confirm-delete/review", $id)}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i>
			   </a>
				
			</div>')
       ->rawColumns(['actions'])
       ->make(true);
    }

	 public function create($ID=NULL)
    {
		 $manager_name = $this->manager_name;
		 $PARENT_ID=105;
	    if($ID)
		{
	     	$navi['route']=route('admin.user-review.store', ['id' => $ID]);
    		$data=app_modal::find($ID);
			if(count((array)$data)==0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
			$data =  app_modal::where('id',$ID)->first();
			return View('admin.'.$this->folder_name.'.edit',compact('navi','data','PARENT_ID','manager_name'));
		}
		//$route= $this->store_url;
		$navi['route']= $this->store_url;
   		return View('admin.'.$this->folder_name.'.edit',compact('navi','PARENT_ID','manager_name'));

    }

	public function store(Request $request, $ID=NULL)
    {
		$records= app_modal::where('id',$ID)->first();  
		$input=Input::all();
		$rules['title'] ='required';
		$rules['message'] ='required';
		$errorMsg = "Oops ! Please fill required fields.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) 
		{
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}

	    $input=$request->all();

		if($ID!='')
		{
			$data=app_modal::find($ID);
			if(count((array)$data)==0)
			{
			$messgae="This is not valid action, data not found.";
			return redirect($this->manager_url)->with('error', trans($messgae));

			}
		}
				
	    $record_save = app_modal::where(['id' => $ID])
	    				->update([
	    					       'title' => $request->title,
	    					       'message' => $request->message,
	    					       'status' => $request->status,
	    						]);
		if($ID!=NULL)
		  $messgae= $this->manager_name." Updated Successfully";
		else
		  $messgae= $this->manager_name." Created Successfully";

		$output['status']			= 'success';
		$output['success_msg']		= $messgae;
		$output['msg']				= $messgae;
		$output['msgHead']			= "Success ! ";
		$output['msgType']			= "success";
		$output['success']			= true;
		$output['slideToTop']		= true;
		$output['url']				= route('admin.user-review');
		return response()->json($output);

    }
 
	public function view($ID)
    {
		 $manager_name = $this->manager_name;

		 $detail =  app_modal::select(['users_reviews.*', 'products.product_title','users.first_name', 'users.last_name',
							 DB::raw("DATE_FORMAT(tbl_users_reviews.created_at,'%d, %M, %Y') as add_date"),
							 DB::raw("DATE_FORMAT(tbl_users_reviews.updated_at,'%d, %M, %Y') as update_date") ])
        					->join('products', 'products.id', 'users_reviews.product_id')
        					->join('users', 'users.id', 'users_reviews.user_id')
        					->where('users_reviews.id',$ID)
        					->first();
		  // $url="storage/app/uploads/blogs/";
		 
		 return View('admin.'.$this->folder_name.'.view', compact('detail','manager_name'));

	 }

	 public function getModalDelete($id =NULL)
	 {
	 	$model = 'User Review';
		$confirm_route = $error = null;
		$confirm_route = route('delete/user-review', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	 }

	 public function destroy($id)
	{
		app_modal::where('id',$id)->forceDelete();
		$success="User Review Deleted Successfully";
		return Redirect::route('admin.user-review')->with('success', $success);
	}

	

	
}
