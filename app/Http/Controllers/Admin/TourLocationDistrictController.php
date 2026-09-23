<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\TourLocationDistrict;
use App\TourLocation;
use Validator;
use DB;
use Session;
use Datatables;
use Redirect;
use App\Helpers\datehelper;

class TourLocationDistrictController extends Controller{

	function __construct()
	{

		$this->date=datehelper::dateformat();
		
		$this->manager_name  = 'Districts';
		$this->folder_name   = 'tourdistrictlocation';
		
		$this->manager_url   = route('admin.tourlocationdistrict');

	}

	//================== Admin   View Function ==================//

	public function index(){
		// get all states
        $states =  TourLocation::get();

         // echo"<pre>";print_r($d);die;
		$manager_name = $this->manager_name;
		$PARENT_ID=113;
		$route_url = route('store.tourlocationdistrict');

		return view('admin.'.$this->folder_name.'.create',compact('PARENT_ID','manager_name','route_url','states'));
	}
	public function data()
	{
		$data =  TourLocationDistrict::select('district.*','states.name as state_name', 
        	        DB::raw("DATE_FORMAT(tbl_district.created_at,'%d %M  %Y') as add_date"))
		 ->join('states', 'states.id', 'district.state_id')
		->get();

		return Datatables::of($data)
		->addColumn('actions', '<div class="btn-group">
			<a class="btn btn-primary" href="{{URL::to("admin/district/edit/$id")}}" title="Edit">
			<i class="fa fa-edit"></i> </a>
			<a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/district/$id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> </a>
			<a class="btn btn-info" data-toggle="modal" data-target="#modal-regular"  href="{{URL::to("admin/district/confirm-status/$id")}}" title="Change Status"><i class="fa fa-info-circle"></i> </a>

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
			$route_url=route('updated.tourlocationdistrict', ['id' => $ID]);
			$data=TourLocationDistrict::find($ID);
			if(count($data)==0)
			{

				$messgae="This is not valid action, data not found.";

				return redirect($this->manager_url)->with('error', trans($messgae));

			}

			$data =  TourLocationDistrict::Select('*')->where('id',$ID)->first();
			$states =  TourLocation::get();
			return View('admin.'.$this->folder_name.'.create',compact('data','states','route_url','PARENT_ID','manager_name'));
		}

		$route_url=route('store.tourlocationdistrict');
		return View('admin.'.$this->folder_name.'.create',compact('route_url','PARENT_ID','manager_name'));

	}

	public function store(Request $request, $ID=NULL)
	{	
		$input=Input::all();
		$rules['name'] ='required';
		$rules['state_id'] = 'required|numeric';
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		$records= TourLocationDistrict::where('id',$ID)->first();
		// $input=$request->all();
		$tournament = TourLocationDistrict::updateOrCreate(['id' => $ID],$input);

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

		$detail =  TourLocationDistrict::Select('*',DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date"),DB::raw("DATE_FORMAT(updated_at,'%d %M  %Y') as update_date"))->where('id',$ID)->first();

		return View('admin.'.$this->folder_name.'.show', compact('detail','manager_name'));

	}

	public function getModalDelete($id =NULL)

	{

		$model = $this->manager_name;

		$confirm_route = $error = null;

		$confirm_route = route('delete.tourlocationdistrict', ['id' => $id]);

		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));

	}
     // change status gt model
	public function chage_status_model($id)
	{   
		$model = $this->manager_name;
        $detail =  TourLocationDistrict::where('id',$id)->first();
		if ($detail->status=='Active') {
			$type = "Inactive";
		}
		else{
              $type = "Active";
		}
		$confirm_route = $error = null;
		$confirm_route = route('change-status.tourlocationdistrict', ['id' => $id]);
		return View('admin/layouts/status_modal_confirmation', compact('error', 'type', 'confirm_route', 'model'));
	}
	// change status
	public function chage_status($id)
	{
		if (is_numeric($id) && !empty($id)) {
			
			$detail =  TourLocationDistrict::where('id',$id)->first();
			if ($detail->status=='Active') {
				TourLocationDistrict::where('id', $id)->update(['status' => 'Inactive']);
                $success = "Status Inactive successsfully.";
			}
			else{
                  TourLocationDistrict::where('id', $id)->update(['status' => 'Active']);
                  $success = "Status Active successsfully.";
			}
			return redirect($this->manager_url)->with('success', $success);
		}
		else
		  return redirect($this->manager_url)->with('error', 'Oops! Something went wrong.');
	}

	public function destroy($id=NULL)

	{ 

		$detail =  TourLocationDistrict::where('id',$id)->first();

		if(isset($detail->image))
		{
			$folderName = '/'.$this->folder_name;
			$filedir = $folderName .'/'. $detail->image;
			Storage::disk('uploads')->delete($filedir);
		}



		TourLocationDistrict::where('id',$id)->delete();

		$success = $this->manager_name." Deleted Succesfully";

		return redirect($this->manager_url)->with('success', $success);

	}

	

}
