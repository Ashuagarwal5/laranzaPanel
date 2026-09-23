<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Redirect;
use App\ContactUsEnquiries;
use App\Chat;
use App\EmailTemplate;
use App\WebsiteSetting;
use App\User;
use App\Threads;
use Sentinel;
use View;
use DB;
use Datatables;
use Input;
use Response;
use Validator;
use Storage;
use App\Helpers\datehelper;

class ChatController extends CodespurController
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
		$PARENT_ID=17;
		$totalRecord	= Chat::count();
		return view('admin.chat.list',compact('PARENT_ID','totalRecord'));
	}
	//=============== All List Data Function ==========================//
	public function data(){		
		 $data = Chat::select(['contact_chat.*','users.full_name'
		 ,DB::raw("DATE_FORMAT(`tbl_contact_chat`.created_at,'%M %d, %Y') as enquiry_date")
		])
		->join('users','users.id','contact_chat.user_id')
		->groupBy('contact_chat.user_id')
		->get();
		//echo "<pre>"; print_r($data); die;
		 return Datatables::of($data)
            ->addColumn('actions', '<a title="View Info" class="btn btn-success btn-xs purple" href="{{URL::to("admin/chat/show/$user_id")}}"data-toggle="modal" data-target="#modal-regular">
				<i class="fa fa-eye"></i> View
				</a>
				<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/chat/$user_id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Enquiry">
				<i class="fa fa-trash"></i> Delete
				</a>
              ')
			->rawColumns(['actions'])
            ->make(true);		
	}
	
	
	public function store(Request $request)
	{   
		$input=$request->all();
		$rules['message']				="required";
		$errorMsg					= "Opps ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		//echo print_r($input); die;
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'no']);
		}				
		else{
				$reply = new Chat();
				$reply->send_by=$request->get('send_by');
				$reply->user_id=$request->get('user_id');
				$reply->message=$request->get('message');		
				$reply->sender_id=Sentinel::getUser()->id;		
				$reply->save();
				
				
				
				 $html_append = '<div class="outgoing_msg">
        <div class="sent_msg_img"> <img src="http://dolovery.sakhtlaunde.in/assets/admin/img/avatar-man.jpg" alt="admin"> </div>
		  <div class="sent_msg">
			<p>'.$reply->message.'</p>
			<span class="time_date">'. date('h:i A') .'   | '.date('d F').'</span> </div>
		</div>';
		
		
		
		
				$output['msg']				= "Reply Succesfully.";
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['status']			= 'success';
				$output['slideToTop']		= true;	
				$output['html_append']		= $html_append;	
				//$output['selfReload']		=true;				
				return json_encode($output);		
	   }
	}
	
	public function view($id){		
		$PARENT_ID=17;
		$detail=Chat::select(['*'
		,DB::raw("DATE_FORMAT(`tbl_contact_chat`.created_at,'%M %d, %Y') as enquiry_date")])->where('id',$id)->first();	
		$messagelist = Chat::select('contact_chat.*','users.profile_photo')->where('user_id',$id)
		
		->join('users','users.id','contact_chat.user_id')
		->get();
		
		
		
		
		
		//echo "<pre>"; print_r($messagelist); die;
		if(count($messagelist)==0){
		$notification = array(
			'message' =>  'Sorry Enquiry Not Found.', 
			'alert-type' => 'warning'
			);
			return redirect('admin/chat')->with($notification);
		}
		$userdetail = User::select('id','first_name','last_name','created_at')->where('id',$id)->first();
		
		
		//echo "<pre>"; print_r($messagelist); die;
		
		return view('admin.chat.view',compact('PARENT_ID','detail','id','userdetail','messagelist','id'));
	}
	
	public function getModalDelete($id = null)
    {
	
		$model = 'Chat';
		$confirm_route = $error = null;
		$enquiries= Chat::where('id', $id)->first();
		if (empty($enquiries)) {
			return Redirect::route('info');
		}
		else
		{
			$confirm_route = route('delete/chat', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }




	public function destroy($id)
	{
		$enquuiries = Chat::find($id);
		$res=$enquuiries->delete();
		return Redirect::route('admin.chat');
	}

 }
