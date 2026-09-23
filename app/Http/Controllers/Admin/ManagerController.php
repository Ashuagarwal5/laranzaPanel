<?php
namespace App\Http\Controllers\Admin;
use App\Manager;
use App\Http\Requests\ManagerRequest;
use Redirect;
use View;
use DB;
use Datatables;
use Input;
use App\Helpers\datehelper;

class ManagerController extends CodespurController
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
		return view('admin.manager.list');
	}

	public function data()
	{
		$manager=Manager::select(['mng_id','manager_name','parent_id','page_set','page_link','display_order','class_name'
		,DB::raw("DATE_FORMAT(`tbl_manager`.created_at,'%d %M  %Y') as add_date")
		//,DB::raw("(DATE_FORMAT(created_at,$this->date)) as add_date")
		])
		->get();

		return Datatables::of($manager)
		->addColumn('actions', '')
		->addColumn('actions','<a class="btn btn-default btn-xs purple" title="Edit Manager" href="{{ route(\'admin.admin_manager.edit\',$mng_id) }}">
		<i class="fa fa-edit"></i>
		</a>
	    <a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/admin_manager/$mng_id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Manager"><i class="fa fa-trash" data-name="user-remove" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="Delete Manager"></i></a>
		')
		->rawColumns(['actions'])
		->make(true);
	}

	public function create($mng_id=null)
	{
		$managerDetail = Manager::select(['manager_name','parent_id','page_set','page_link','display_order','class_name'])
		->where('mng_id',$mng_id)
		->first();
		return view('admin.manager.create',compact('managerDetail'));
	}

	public function store(ManagerRequest $request,$mng_id=null)
	{
		$manager = $request->all();

		if($mng_id!=null)
		$message="Manager Updated Successfully";
		else
		$message="Manager Created Successfully";
		Manager::updateOrCreate(['mng_id' => $mng_id], $manager);
		return redirect('admin/admin_manager')->with('success', trans($message));
	}


   public function getModalDelete($id = null)
    {
		//echo "hello"; die;
		$model = 'Manager';
		$confirm_route = $error = null;
		$manager= Manager::where('mng_id', $id)->first();
		if (empty($manager)) {

			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/admin_manager', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }




	public function destroy($id)
	{
		$manager = Manager::find($id);
		$res=$manager->delete();
		return Redirect::route('admin_manager');


	}


 }
