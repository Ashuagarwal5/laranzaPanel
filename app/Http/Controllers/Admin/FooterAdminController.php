<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Input;
use View;
use App\FooterMenu;
use Validator;
use DB;
use Datatables;
use Illuminate\Support\Facades\Storage;
use Redirect;
use App\Helpers\datehelper;
class FooterAdminController extends Controller
{
    function __construct()
	{
						$this->date=datehelper::dateformat();
	}	

	//================== Admin   View Function ==================//

	public function index(){	

		$PARENT_ID=103;
		return view('admin.footermenu.list',compact('PARENT_ID'));
	}
	 public function data()
    {
        $data = FooterMenu::select(['ft_id','link_showing_name','shownig_order','link_address','link_showing_in_column','created_at',DB::raw("DATE_FORMAT(created_at,'%d %M  %Y') as add_date")] )
        ->orderBy('ft_id', 'desc')->get();
		 return Datatables::of($data)
		         ->addColumn('actions', '<div class="btn-group">    
            <a class="btn btn-primary btn-xs" href="{{URL::to("admin/footer/edit/$ft_id")}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>
			 <a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/footer/$ft_id/confirm-delete")}}" title="Delete"><i class="fa fa-trash"></i> Delete</a>
		       	</div>
              		')
       ->rawColumns(['actions'])
       ->make(true);
    }
	 public function create($ID=NULL)
    {
		$PARENT_ID=103;
	      if($ID)
		{
			$route=route('updated.footer', ['ft_id' => $ID]);
			$data=FooterMenu::find($ID);
			if(count($data)==0)
			{
				$messgae="This is not valid action, data not found.";
				return redirect('admin.footer')->with('error', trans($messgae));
			}
			$data =  FooterMenu::Select('ft_id','link_type','link_address','link_showing_name','blank_target','link_showing_in_column','shownig_order')->where('ft_id',$ID)->first();
			return View('admin.footermenu.create',compact('data','route','PARENT_ID'));
		}
			$route=route('store.footer');
	   		return View('admin.footermenu.create',compact('route','PARENT_ID'));
    }	

    public function store($ID=NULL)
    {
		$input=Input::all();
        $rules['link_showing_name'] = 'required';
		$rules['link_type'] ='required';
		$rules['link_address'] = 'required';
		$rules['shownig_order'] = 'required';
		$rules['link_showing_in_column'] = 'required';
		$errorMsg="Opps ! Some Error Occured. Please Try Again.";
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}	
		if($ID!=''){
		$data=FooterMenu::find($ID);
			if(count($data)==0){
			$message="This is not valid action,Header data not found.";
			return redirect('admin.footer')->with('error', trans($message));
			}			
		}
		$footer = FooterMenu::updateOrCreate(['ft_id' => $ID],$input);
        if($ID!=NULL)
           $message="Footer Menu Updated Successfully";
        else
            $message="Footer Menu Created Successfully";
		if ($footer->save()) 
		{
			$output['status']='success';
			$output['success']=true;
			$output['success_msg']		= $message;
			$output['msg']				= $message;
			$output['msgHead']			= "Success ! ";
			$output['msgType']			= "success";
			$output['slideToTop']=true;;
			$output['url']=route('admin.footer');

		    echo json_encode($output);die;
		} 
		else {
			return Redirect::route('admin/footer/create')->withInput()->with('error', trans('event/message.error.create'));
		}		
    }	
    
    public function getModalFinalDelete($id = null)
    {
		$model = 'Footer';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete.footer', ['ft_id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
	 public function permanentDelete($id)
	{
		FooterMenu::where('ft_id',$id)->withTrashed()->forceDelete();
	   $success ="Footer Permanently Deleted Succesfully";
	 return Redirect::route('admin.footer')->with('success', $success);
	}
}
