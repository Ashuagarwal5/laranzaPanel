<?php

namespace App\Http\Controllers\Admin;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\States;
use Validator;
use DB;
use Datatables;
use Redirect;
use App\Helpers\datehelper;

class TourLocationController extends Controller{

	function __construct()
	{

		$this->date=datehelper::dateformat();
		
		$this->manager_name  = 'States';
		$this->folder_name   = 'tourlocation';
		
		$this->manager_url   = route('admin.tourlocation');

	}

	//================== Admin   View Function ==================//

	public function index(){
		
		$manager_name = $this->manager_name;
		$PARENT_ID=113;
		$route_url = route('store.tourlocation');
        // $data =  States::select('*')->get();
        // echo '<pre>';
        // print_r($data);die;

		return view('admin.'.$this->folder_name.'.create',compact('PARENT_ID','manager_name','route_url'));
	}
	public function data()
	{
		$data =  States::select(['*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")])->get();

		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" href="{{URL::to("cpmin/state/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/state/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			</div>
			')
		->rawColumns(['actions'])
		->make(true);
	}

	public function create($ID=NULL)
	{
		$manager_name = $this->manager_name;
		$PARENT_ID=113;
		if($ID)
		{
			$route_url=route('updated.tourlocation', ['id' => $ID]);
			$data=States::find($ID);
			if(empty($data))
			{

				$messgae="This is not valid action, data not found.";

				return redirect($this->manager_url)->with('error', trans($messgae));

			}

			$data =  States::Select('*')->where('id',$ID)->first();
			return View('admin.'.$this->folder_name.'.create',compact('data','route_url','PARENT_ID','manager_name'));

		}

		$route_url=route('store.tourlocation');
		return View('admin.'.$this->folder_name.'.create',compact('route_url','PARENT_ID','manager_name'));

	}

	public function store(Request $request, $ID=NULL)
	{
		$input=Input::all();
		$rules['name'] ='required';
		$rules['code'] ='required';
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records= States::where('id',$ID)->first();
		// $input=$request->all();
		$tournament = States::updateOrCreate(['id' => $ID],$input);




		if($ID!=NULL)
			$messgae= $this->manager_name." Updated Successfully";
		else

			$messgae= $this->manager_name." Created Successfully";

		if ($tournament) {

			//TourAmenities::where('id', $tournament->id);

			$output['status']='success';

			$output['step']='step1';

			$output['success']=true;

			$output['slideToTop']=true;

			$output['success_msg']=$messgae;

			$output['url']=$this->manager_url;

			echo json_encode($output);die;

		} else {

			return redirect($this->manager_url.'/create')->with('error', 'Oops! Something went wrong.');


		}

	}

	public function show($ID)

	{
		
		$manager_name = $this->manager_name;

		$detail =  States::Select('*',DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"),DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id',$ID)->first();

		return View('admin.'.$this->folder_name.'.show', compact('detail','manager_name'));

	}

	public function getModalDelete($id =NULL)

	{

		$model = $this->manager_name;

		$confirm_route = $error = null;

		$confirm_route = route('delete.tourlocation', ['id' => $id]);

		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	}

	public function destroy($id=NULL)

	{

		$detail =  States::where('id',$id)->first();

		if(isset($detail->image))
		{
			$folderName = '/'.$this->folder_name;
			$filedir = $folderName .'/'. $detail->image;
			Storage::disk('uploads')->delete($filedir);
		}



		States::where('id',$id)->delete();

		$success = $this->manager_name." Deleted Succesfully";

		return redirect($this->manager_url)->with('success', $success);

	}

	

}
