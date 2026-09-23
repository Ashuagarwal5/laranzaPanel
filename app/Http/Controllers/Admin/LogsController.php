<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Logs;
use Redirect;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Session;
use View;
use DB;
use Datatables;
use Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;

class LogsController extends CodespurController
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
	public function adminLogIndex()
	{	
		//echo "done"; die;
		$PARENT_ID=7;
		return view('admin.logs.admin_log_list',compact('PARENT_ID'));
	}
	public function sellerLogIndex()
	{	
		//echo "done"; die;
		$PARENT_ID=7;
		return view('admin.logs.seller_log_list',compact('PARENT_ID'));
	}
	public function userLogIndex()
	{	
		//echo "done"; die;
		$PARENT_ID=7;
		return view('admin.logs.user_log_list',compact('PARENT_ID'));
	}
	
	public function adminLogData(){
		
		
		$data = Logs::select(['id','name','type','ip','browser'
			,DB::raw("(DATE_FORMAT(`tbl_logs`.created_at,'%d %M  %Y %h:%i:%s')) as add_date")])
			->where('type','Admin')
			->orderBy('id','desc')
			->get();
		
		 return Datatables::of($data)->make(true);
		
	}
	
	public function sellerLogData(){
		
		
		$data = Logs::select(['id','name','type','ip','browser'
			,DB::raw("(DATE_FORMAT(`tbl_logs`.created_at,'%d %M  %Y %h:%i:%s')) as add_date")])
			->where('type','Seller')
			->orderBy('id','desc')
			->get();
		
		 return Datatables::of($data)->make(true);
		
	}
	
	public function userLogData(){
		
		
		$data = Logs::select(['id','name','type','ip','browser'
			,DB::raw("(DATE_FORMAT(`tbl_logs`.created_at,'%d %M  %Y %h:%i:%s')) as add_date")])
			->where('type','User')
			->orderBy('id','desc')
			->get();
		
		 return Datatables::of($data)->make(true);
		
	}
	
	public function getModalDelete($slug)
	{	
		$confirm_route = $error = null;	
		if($slug=='admin')
		{
			$model='Admin Logs';
			$confirm_route = route('admin_logs.delete', ['slug' => $slug]);
		}
		if($slug=='seller')
		{
			$model='Seller Logs';
			$confirm_route = route('seller_logs.delete', ['slug' => $slug]);
		}
		if($slug=='user')
		{
			$model='User Logs';
			$confirm_route = route('user_logs.delete', ['slug' => $slug]);
		}
		
		
		return View('admin/layouts/delete_modal_confirmation', compact('confirm_route','model','error'));
	}
	 public function deleteRecord($slug){
		 // echo "delete"; die;
		 try {	
				if($slug=='admin')
				{
					$result=DB::table('logs')->where('type','Admin')->delete();
				}
				if($slug=='seller')
				{
					$result=DB::table('logs')->where('type','Seller')->delete();
				}
				if($slug=='user')
				{
					$result=DB::table('logs')->where('type','User')->delete();
				}
				
				
				$notification = array(
					'message' => 'Logs deleted successfully.', 
					'alert-type' => 'success'
					);
			if($slug=='admin')
			{
				return Redirect::to('cpmin/admin_logs')->with($notification);
			}
			if($slug=='seller')
			{
				return Redirect::to('cpmin/seller_logs')->with($notification);
			}
			if($slug=='user')
			{
				return Redirect::to('cpmin/user_logs')->with($notification);	
			}
			
		} catch (ModelNotFoundException $e) {
			return Response::view('404', array(), 404);
		}
	}
	
	
 }
