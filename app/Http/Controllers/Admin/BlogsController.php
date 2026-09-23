<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\Blog as app_modal;
use App\BlogComment;
use App\Answer;
use Validator;
use DB;
use App\WebsiteSetting;
use Datatables;
use Redirect;
use App\Helpers\datehelper;
use App\Helpers\IPInfoDB;

class BlogsController extends Controller{

    function __construct()
	{
		//  $this->date=datehelper::dateformat();
		
		 $this->manager_name  = 'Blogs';
		 $this->folder_name   = 'blogs';
		// app_modal     = app_modal;
		 $this->manager_url   = route('admin.blogs');
		 $this->store_url     = route('admin.blogs.store');
		//  $this->date_format   = WebsiteSetting::select('dateformat')->first();

	}

	//================== Admin   View Function ==================//
	public function index()
	{
		$manager_name = $this->manager_name;
		$PARENT_ID=83;
		$route_create = route('blogs.create');
		$route_data = route('admin.blogs.data');

		return view('admin.'.$this->folder_name.'.list',compact('PARENT_ID','manager_name','route_create','route_data'));
		
	}

	 public function data()
    {
        $data =  app_modal::select(['blog.*',DB::raw("DATE_FORMAT(tbl_blog.created_at,'%d, %M, %Y') as add_date")] )
           ->orderBy('id', 'desc')->get();

		 return Datatables::of($data)
		 ->addColumn('actions', '<div class="btn-group">
				<a title="View Record" class="btn btn-success" href="{{URL::to("admin/blogs/show/$id")}}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>
				<a class="btn btn-primary" href="{{URL::to("admin/blogs/edit/$id")}}" title="Edit Record"><i class="fa fa-edit"></i> </a>
				<a data-original-title="Delete Record"   class="btn btn-danger enable-tooltip" href="{{URL::to("admin/blogs/$id/confirm-delete")}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i></a>
			</div>')
       ->rawColumns(['actions'])
       ->make(true);
    }

	 public function create($ID=NULL)
    {
    	// $d  = app_modal::get()->toArray();
     //     echo "<pre>";
     //     print_r($d);
         
		 $manager_name = $this->manager_name;
		 $navi['back_url']= $this->manager_url; 
		 $PARENT_ID=83;
	    if($ID)
		{
	     	$navi['route']=route('admin.blogs.edit.store', ['id' => $ID]);
    		$data=app_modal::find($ID);
			if(count($data)==0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
			$data =  app_modal::where('id',$ID)->first();
			return View('admin.'.$this->folder_name.'.edit',compact('navi','data','route','PARENT_ID','manager_name'));
		}
		//$route= $this->store_url;
		$navi['route']= $this->store_url;
   		return View('admin.'.$this->folder_name.'.edit',compact('route','navi','PARENT_ID','manager_name'));

    }

	public function store(Request $request, $ID=NULL)
    {
		$records= app_modal::where('id',$ID)->first();  
		$input=Input::all();
		// $rules['blog_category'] ='required';
		$rules['blog_title'] ='required';
		$rules['blog_content'] ='required';
		$rules['display_order'] ='numeric';
		// $rules['short_description'] ='required';

		// if($ID !=NULL)
		// {
		// 	if ($file = $request->file('image'))
	 //      	$rules['image']				= "mimes:jpg,jpeg,png";
		// }
		// else
		// {
		//   $rules['image']				= "required|mimes:jpg,jpeg,png";
		// }  
		$errorMsg = "Opps ! Please fill required fields.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) 
		{
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}

	    $input=$request->all();

		if($ID!='')
		{
			$data=app_modal::find($ID);
			if(count($data)==0)
			{
			$messgae="This is not valid action, data not found.";
			return redirect($this->manager_url)->with('error', trans($messgae));

			}
		}
				
	    $record_save = app_modal::updateOrCreate(['id' => $ID],$input);
		if($ID!=NULL)
		  $messgae= $this->manager_name." Updated Successfully";
		else
		  $messgae= $this->manager_name." Created Successfully";

		if ($record_save->save()) 
		{
			$output['status']			= 'success';
			$output['success_msg']		= $messgae;
			$output['msg']				= $messgae;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			$output['url']				= route('admin.blogs');
			return response()->json($output);
		} 
		else 
		{
			return redirect($this->manager_url.'/edit')->with('error', 'Oops! Something went wrong.');
		}

    }
 
	public function view($ID)
    {
		 $manager_name = $this->manager_name;

		 $detail =  app_modal::select(['blog.*',DB::raw("DATE_FORMAT(tbl_blog.created_at,'%d, %M, %Y') as add_date")] )
		 ->where('id',$ID)->first();
		  // $url="storage/app/uploads/blogs/";
		 
		 return View('admin.'.$this->folder_name.'.view', compact('detail','manager_name'));

	 }

	 public function getModalDelete($id =NULL)
	 {
	 	$model = 'Blog';
		$confirm_route = $error = null;
		$confirm_route = route('delete/blogs', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	 }

	 public function destroy($id)
	{
		app_modal::where('id',$id)->forceDelete();
		$success="Blog Deleted Successfully";
		return Redirect::route('admin.blogs')->with('success', $success);
	}

	//blogg comments
	public function blogs_comments()
	{
		// $d = BlogComment::get()->toArray();
		// echo "<pre>";
		// print_r($d);
		$PARENT_ID=83;
		$data = BlogComment::where('deleted_at', null)->get();
		return view('admin.'.$this->folder_name.'.all-blogs-comments',compact('PARENT_ID','data'));
		
         
	}

	public function blogs_comments_data()
	{
      $data =  BlogComment::select(['blogscomments.*', 'blog.blog_title',
      	    DB::raw("DATE_FORMAT(tbl_blogscomments.created_at,'%d, %M, %Y') as add_date")] )
           ->join('blog', 'blog.id', 'blogscomments.blog_id')
           ->orderBy('blogscomments.id', 'desc')
           ->get();
		 return Datatables::of($data)
		 ->addColumn('actions', '<div class="btn-group">
				<a title="View Record" class="btn btn-success" href="{{URL::to("admin/blogs/show-comment/$id")}}" data-toggle="modal" data-target="#modal-email"><i class="fa fa-eye"></i></a>
				<a class="btn btn-primary" href="{{URL::to("admin/blogs/edit-comment/$id")}}" title="Edit Record"><i class="fa fa-edit"></i> </a>
				<a data-original-title="Delete Record"   class="btn btn-danger enable-tooltip" href="{{URL::to("admin/blogs/$id/confirm-delete-comment")}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i></a>
			</div>')
       ->rawColumns(['actions'])
       ->make(true);
	}

	public function view_comment($id)
	{
		$manager_name = "Blog Comment";
		 $detail =  BlogComment::select(['*',DB::raw("DATE_FORMAT(tbl_blogscomments.created_at,'%d, %M, %Y') as add_date")] )
		 ->where('id',$id)->first();
		 
		 return View('admin.'.$this->folder_name.'.view-comment', compact('detail','manager_name'));
	}

	public function edit_comment($id)
	{
		$PARENT_ID  = 83;
	   $data = BlogComment::find($id);	
	   if (isset($data)) 
	   {
	   	  return View('admin.'.$this->folder_name.'.edit-comment', compact('data', 'PARENT_ID'));
	   }
	   else
	   {
	   	 return Redirect::route('blogs/blogs-comments')->with('success', 'Something went wrong');
	   }
	}

	public function store_comment(Request $request)
	{
		$rules['name'] = 'required';
		$rules['email'] = 'required';
		$rules['message'] = 'required';
		$rules['blog_id'] = 'required|numeric';
		$rules['status'] = 'required';

		$errorMsg = "Opps ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) 
		{
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		else
		{
			try{

            BlogComment::where('id', $request->blog_id)
		   ->update([
		           'name' => $request->name,
		           'email' => $request->email,
		           'message' => $request->message,
		           'status' => $request->status,
		        ]);

		   $output['status']			= 'success';
			$output['success_msg']		= "Blog Comment Updated Successfully";
			$output['msg']				= "Blog Comment Updated Successfully";
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['success']			= true;
			$output['slideToTop']		= true;
			
			//$output['url']				= route('admin.products.edit',['id'=>$pro->id]);
			$output['url']				= route('blogs/blogs-comments');
			
			return response()->json($output);
			}
			catch(\Exception $e)
			{
	           return response()->json(['errorArray'=>[$e->getMessage()],'error_msg'=>$e->getMessage(),'slideToTop'=>'yes']);
			}
		}
		
	}

	public function getCommentModalDelete($id =NULL)
	 {
	 	$model = 'Blog Comment';
		$confirm_route = $error = null;
		$confirm_route = route('delete/blogs-comment', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	 }

	 public function destroy_comment($id)
	 {
	 	BlogComment::where('id',$id)->forceDelete();
		$success="Blog Comment Deleted Successfully";
		return Redirect::route('blogs/blogs-comments')->with('success', $success);
	 }

	
}
