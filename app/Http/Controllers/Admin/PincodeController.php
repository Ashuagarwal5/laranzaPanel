<?php
namespace App\Http\Controllers\Admin;
use Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\Pincode;
use App\TourLocation;
use App\States;
use Validator;
use DB;
use Datatables;
use Redirect;
use App\Helpers\datehelper;

class PincodeController extends Controller{

	function __construct()
	{

		$this->date=datehelper::dateformat();
		
		$this->manager_name  = 'Pincodes';
		$this->folder_name   = 'pincode';
		
		$this->manager_url   = route('admin.pincode');

	}

	//================== Admin   View Function ==================//

	public function index(Request $request)
	{
		
		$manager_name = $this->manager_name;
		$PARENT_ID=113;
		$search='';
		if($request->search)
		{
			$search=$request->search;
		}

		//$route_url = route('store.pincode');
       // $data =  Pincode::select('id')->get();
		$data =  Pincode::select(['*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")]);
		if($request->search)
		{
			$data =$data->where(function($data) use($search) {
				$data->where('pincode', 'like', '%,' . $search . '%');
				$data->orWhere('pincode', 'like', $search . '%');
				$data->orWhere('pincode', 'like', '%' . $search);
				$data->orWhere('state', 'like', '%'.$search.'%');
				$data->orWhere('state', 'like', $search.'%');
				$data->orWhere('state', 'like', '%'.$search);
				$data->orWhere('city', 'like', '%'.$search.'%');
				$data->orWhere('city', 'like', $search.'%');
				$data->orWhere('city', 'like', '%' .$search);
				$data->orWhere('district', 'like', '%'.$search.'%');
				$data->orWhere('district', 'like',  $search.'%');
				$data->orWhere('district', 'like', '%'.$search);
			});
		}
		$data =$data->paginate('150');

        // echo "<pre>".count($data);print_r($data);die;
		return view('admin.'.$this->folder_name.'.pincodes',compact('PARENT_ID','manager_name','data'));
	}
	public function data()
	{
		$data =  Pincode::select(['*', DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")])->get();

		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" href="{{URL::to("cpmin/pincode/edit/$id")}}" title="Edit"><i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/pincode/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			</div>
			')
		->rawColumns(['actions'])
		->make(true);
	}

	public function create($ID=NULL)
	{
		$manager_name = $this->manager_name;
		$PARENT_ID=113;
		// $state=Pincode::select('state')->groupBy('state')->where('status','Active')->get();
		$state=States::get();
		if($ID)
		{
			$route_url=route('updated.pincode', ['id' => $ID]);
			$data=Pincode::find($ID);
			if(empty($data))
			{
				$messgae="This is not valid action, data not found.";
				return redirect($this->manager_url)->with('error', trans($messgae));
			}
			$data =  Pincode::Select('*')->where('id',$ID)->first();
			return View('admin.'.$this->folder_name.'.create',compact('data','route_url','PARENT_ID','manager_name','state'));
		}

		$route_url=route('store.pincode');
		return View('admin.'.$this->folder_name.'.create',compact('route_url','PARENT_ID','manager_name','state'));

	}

	public function store(Request $request, $ID=NULL)
	{
		$input=Input::all();
		$rules['state'] ='required';
		$rules['district'] ='required';
		$rules['city'] ='required';
		if ($ID != null) {
			$rules['pincode'] ='required';
		}
		else {
			$rules['pincode'] ='required|unique:pincodes,pincode,'.$ID;
		}
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records= Pincode::where('id',$ID)->first();
		// $input=$request->all();
		$tournament = Pincode::updateOrCreate(['id' => $ID],$input);
		if($ID!=NULL)
			$messgae= $this->manager_name." Updated Successfully";
		else
			$messgae= $this->manager_name." Created Successfully";
		if ($tournament) {
			//TourAmenities::where('id', $tournament->id);
			$output['status']='success';
			$output['msgType']='success';
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

		$detail =  Pincode::Select('*',DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"),DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id',$ID)->first();

		return View('admin.'.$this->folder_name.'.show', compact('detail','manager_name'));

	}

	public function getModalDelete($id =NULL)

	{

		$model = $this->manager_name;

		$confirm_route = $error = null;

		$confirm_route = route('delete.pincode', ['id' => $id]);

		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	}

	public function destroy($id=NULL)

	{

		$detail =  Pincode::where('id',$id)->first();

		if(isset($detail->image))
		{
			$folderName = '/'.$this->folder_name;
			$filedir = $folderName .'/'. $detail->image;
			Storage::disk('uploads')->delete($filedir);
		}



		Pincode::where('id',$id)->delete();

		$success = $this->manager_name." Deleted Succesfully";

		return redirect($this->manager_url)->with('success', $success);

	}

	

}
