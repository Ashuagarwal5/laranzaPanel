<?php
namespace App\Http\Controllers\Admin;
use App\Http\Requests;
use App\Http\Requests\BanipRequest;
use Mail;
use Redirect;
use Sentinel;
use View;
use App\Banip;
use DB;
use Datatables;
use Cache;
use App\Helpers\datehelper;
class BanipController extends CodespurController
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
		$PARENT_ID = 7;
	      
		return view('admin.banip.list',compact('PARENT_ID'));
	}

public function data()
	{
		
		$banip = Banip::select(['ban_id','ban_ip'
		,DB::raw("(DATE_FORMAT(tbl_ban_ip.created_at,'%d %M  %Y')) as add_date")])
		
		->orderBy('ban_id','desc')->get();
	
		return Datatables::of($banip)
		->addColumn('actions','<a data-toggle="modal" data-target="#delete-confirm-for-all" href="{{URL::to("admin/banip/$ban_id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete IP"><i class="fa fa-trash" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="Delete IP"></i></a>')
		->rawColumns(['actions'])
		->make(true);
	}

public function create($blog_id=null)
	{
	    $PARENT_ID = 7;
		return view('admin.banip.create',compact('PARENT_ID'));
	}
	
public function store(BanipRequest $request)
	{ 
		Cache::flush('banIpList');
		$banip = new Banip();
		$banip->ban_ip=$request->get('ban_ip');
		$banip->save();
		$notification = array(
			'message' =>  'Ban IP Created Successfully.', 
			'alert-type' => 'success'
			);
		return redirect('admin/banip')->with($notification);
	}
	

    
	public function getModalDelete($id = null)
	{
		$model = 'Ban IP';
		$confirm_route = $error = null;

		$confirm_route = route('delete/banip', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	}
	
	
	public function destory($id)
	{   
		Cache::flush('banIpList');
		$banip = Banip::find($id);
		//$res=$banip->delete();
		$banip->forceDelete();
		$notification = array(
			'message' =>  'Ban IP Deleted Successfully.', 
			'alert-type' => 'success'
			);
		return redirect('admin/banip')->with($notification);
		

	}
	
	 public function getModalRestore($id = null)
    {
		$PARENT_ID = 7;
		$model = 'Ban IP';
		$confirm_route = $error = null;
		$confirm_route = route('restore/banip', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route','PARENT_ID'));
    }
	  
	public function restoreDeletedBanip($brand_id)
	{
	    	Cache::flush('banIpList');
	    	Banip::withTrashed()->find($brand_id)->restore();
		 return Redirect::route('banip/deletedbanip');
	}
	
	public function listDeletedBanip()
	{
			$PARENT_ID=7;
		return view('admin.banip.deletedbanip',compact('PARENT_ID'));
	}
	
	public function listDeletedData(){

		
			$banip = Banip::select(['ban_id','ban_ip'
			,DB::raw("(DATE_FORMAT(created_at,$this->date)) as add_date")])->onlyTrashed()->get();
		

		return Datatables::of($banip)
		->addColumn('actions', '<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/banip/$ban_id/confirm-restore")}}" class="btn btn-xs btn-default">Restore</a>
		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/banip/$ban_id/final-delete")}}" class="delval btn btn-xs btn-danger" title="Permanent Delete"><i class="fa fa-trash"></i></a>
		')
		->make(true);
	}
	
	public function getModalFinalDelete($id = null)
    {
		$model = 'Ban IP';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete/banip', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
    
    public function permanentDelete($id)
	{   
		 Banip::where('ban_id',$id)->withTrashed()->forceDelete();
		
		 return Redirect::route('banip/deletedbanip');

	}
	
	
	
	 

}
