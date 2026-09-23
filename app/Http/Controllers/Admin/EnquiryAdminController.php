<?php
namespace App\Http\Controllers\Admin;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\FeedbackRequest;
use App\Http\Requests\ContactReplyRequest;
use App\Http\Requests\ReplyRequest;
use Lang;
use Mail;
use Redirect;
use View;
use App\Page;
use App\ContactUs;
use App\Feedback;
use App\EmailTemplate;
use App\WebsiteSetting;
use Illuminate\Support\Facades\Input;
use App\EnquiryReply;
use DB;
use Datatables;
use Request;
use URL;
use App\Helpers\datehelper;
class EnquiryAdminController extends CodespurController
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
	
	public function getUrlLastPath(){
		
		$uri_path = $_SERVER['REQUEST_URI']; 
		$uri_parts = explode('/', $uri_path);
		$request_url = end($uri_parts);
		return $request_url;	
	}
    public function index()
    {
	      // $d = Contactus::get();
	      // echo"<pre>";
	      // print_r($d);
	      // die;
      $request_url =  $this->getUrlLastPath();
	  $PARENT_ID = 17;
     return view('admin.enquiry.list',compact('PARENT_ID','request_url'));
    }

    public function data()
    {
	    $request_url = 	 Input::get('request_url');
		$enquiry = Contactus::select(['*',DB::raw("(Select COUNT(*) from tbl_enquiry_reply as enquiry_reply where enquiry_reply.enq_id=tbl_enquiry.enq_id group by enq_id ) as totalreply"),DB::raw("DATE_FORMAT(tbl_enquiry.created_at,'%d, %M - %Y') as add_date")])
		->where('enq_type',$request_url)
		->orderBy('enq_id', 'desc');
        return Datatables::of($enquiry)
             ->addColumn('actions', '<div class="btn-group">
             <a title="View Info" class="btn btn-default" href="{{ route(\'enquiry/view\',$enq_id) }}" data-toggle="modal" data-target="#modal-large">
				<i class="fa fa-eye"></i>
              
              </a>
			  <a class="btn btn-danger" data-toggle="modal" data-target="#modal-regular" href="{{URL::to("admin/enquiry/$enq_id/confirm-delete")}}" title="Delete Enquiry">
				<i class="fa fa-trash"></i>
			 
			  </a>
		 </div>
      ')
    ->rawColumns(['actions'])
            ->make(true);
     /* 
    <a title="Enquiry Reply" class="btn btn-primary" href="{{ URL::to("admin/enquiry/reply/".$enq_id) }}">
	 <i class="fa fa-reply"></i>
    </a>
     */
   }

	public function viewRecord($enqID)
	{
		
		$detail=Contactus::select(['enq_id','enquiry.name','enquiry.image','enquiry.enq_type',
			                       'enquiry.email','enquiry.phone','enquiry.message','enquiry.created_at',DB::raw("DATE_FORMAT(tbl_enquiry.created_at,'%d  %M %Y')")])
		->where('enq_id',$enqID)
		->first();
		
		//echo  ; die;		
		if(count((array)$detail)==0){
		$messgae="This is not valid action, Enquiry data not found.";
		return redirect('admin/enquiry')->with('error', trans($messgae));
		}
		Contactus::where('enq_id',$enqID)->update(['read_status' => 'Yes']);
		$replytMsg = EnquiryReply::select(['enq_r_id','enq_id','reply',DB::raw("DATE_FORMAT(created_at,$this->date) as add_date")])
		->where('enq_id',$enqID)->orderby('enq_r_id','desc')->get();
		//echo  "<pre>"; print_r($replytMsg); die;
		return View('admin.enquiry.view',compact('detail','replytMsg'));

	}

  public function enquiryAllReply($enqID)
  {
	$replytMsg = EnquiryReply::select(['enq_r_id','enq_id','reply',DB::raw("DATE_FORMAT(created_at,'%d, %M - %Y') as add_date")])->orderby('enq_r_id','desc')->where('enq_id',$enqID)->get();
    return View('admin.enquiry.allreplyview',compact('replytMsg'));

	}
    public function deleteconfirm($enqID)
     {
        $enquiry= Contactus::where('enq_id', $enqID)->first();
          $confirm_route = $error = null;
            // Check if we are not trying to delete ourselves
           if(count((array)$enquiry)==0){
			$messgae="This is not valid action, Enquiry data not found.";
			return redirect('admin/enquiry')->with('error', trans($messgae));
			}
           
		 $confirm_route = route('admin/delete/enquiry', ['id' => $enqID]); 
		
         $model= "Enquiry";
       return View('admin/layouts/delete_modal_confirmation',compact('error','model','confirm_route'));
     }
      public function destroy($enqID)
     {
		 $enquiry= Contactus::where('enq_id', $enqID)->first();
        if(count((array)$enquiry)==0){
			$messgae="This is not valid action, Enquiry data not found.";
			return redirect('admin/enquiry')->with('error', trans($messgae));
			}
        $User_Vehicle_delete= Contactus::where('enq_id', $enqID)->delete();
		$success ="Enquiry Deleted Succesfully";
		
		  $request_url =  $enquiry->enq_type;
            
            
           $rreturn_url =   URL::to('admin/'.$enquiry->enq_type);
           
           return redirect($rreturn_url)->with('success', $success);
           
		//return Redirect::route('admin.enquiry')->with('success', $success); 
     }
    public function enquiryReply($enqID)
    {		       
    	$PARENT_ID = 17;
        $enquiry= Contactus::where('enq_id', $enqID)->first();
        return View('admin.enquiry.reply',compact('enquiry', 'PARENT_ID'));
	}

   public function enquiryReplyPost(ReplyRequest $request,$enqID)
   {
   	    $adminEmail = WebsiteSetting::getGeneralSetting()->contact_email;
		//$goesefromemail = WebsiteSetting::getGeneralSetting()->goes_from_email;
		$enquiry= Contactus::where('enq_id', $enqID)->first();
		$message=Input::get('message');
        $enquiryreply=new EnquiryReply();
		$enquiryreply['enq_id'] =  $enqID;
		$enquiryreply['reply'] =  $message;
//		echo $enquiryreply; die;
		$enquiryreply->save();
		$template  =  EmailTemplate::Select('em_tm_id','title','subject','message')->where('em_tm_id',6)->first();
      
      
         $sitelogo='<img src="'.asset(config('constants.frontend.logo')).'">';
       
		$siteData=WebsiteSetting::select('goes_from_email','admin_email','goes_from_name','site_name')->where('id',1)->first();

		$goes_from_email= WebsiteSetting::getGeneralSetting()->goes_from_email;
		$siteName = $siteData->site_name;

		$rec_email=$enquiry->email;
		$rec_name=$enquiry->name;
		  
		  $str=str_replace("{#logo}",$sitelogo,$template->message);
          $str=str_replace("{#name}",$enquiry->name,$str);
          $str=str_replace("{#email}",$enquiry->email,$str);
          $str=str_replace("{#message}",$enquiry->message,$str);
		  $str=str_replace("{#subject}",$template->subject,$str);
		  $str=str_replace("{#site_title}",$siteName,$str);

          $str=str_replace("{#replymessage}",$message,$str);
          
		
		
		Mail::send('email.email',compact('str'), function ($m) use ($template,$goes_from_email,$rec_name,$rec_email,$siteName) {
				$m->from($goes_from_email, $siteName);
				$m->to($rec_email, $rec_name);
				$m->subject($template->subject);
				});
				
				

				
      
            $success = Lang::get('Reply send successfully');
            
          $request_url =  $enquiry->enq_type;
            
            
           $rreturn_url =   URL::to('admin/'.$enquiry->enq_type);
           
           return redirect($rreturn_url)->with('success', $success);
           
          // echo  $rreturn_url; die;
           
                // Redirect to the user page
		
	   // return Redirect::$rreturn_url->with('success', $success);
	}
	//----------------------------------//
	public function getModalRestore($id = null)
	{
		$model = 'Enquiry';
		$confirm_route = $error = null;
		$confirm_route = route('restore/enquiry', ['id' => $id]);
		return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function restoreDeletedenuiry($enu_id)
	{
		Contactus::withTrashed()->where('enq_id',$enu_id)->restore();
		  $success="Enquiry Restore Successfully";
		return Redirect::route('enquiry/deletedfeedback')->with('success', $success);
	}
	public function listDeletedenquiry()
	{
		return view('admin.enquiry.deletedlist');
	}
	public function listDeletedData(){
		$enquiry = Contactus::select(['enq_id','name','email','subject','read_status',DB::raw("DATE_FORMAT(created_at,'%d, %M - %Y') as add_date")])->orderBy('enq_id', 'desc')->            onlyTrashed()->get();
		return Datatables::of($enquiry)
		->addColumn('actions',
	 '<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/enquiry/$enq_id/confirm-restore")}}" class="btn btn-small btn-default"title="Restore">														         <i class="fa fa-undo"> Restore</i></a>
		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/enquiry/$enq_id/final-delete")}}" class="delval btn btn-small btn-danger" title="Permanent Delete"><i class="fa fa-trash"> Delete</i></a>
		')
		->make(true);
	}
	public function getModalFinalDelete($id = null)
    {
		$model = 'Enquiry';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete/enquiry', ['id' => $id]);
        return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
    }
    public function permanentDelete($id)
	{
		 Contactus::where('enq_id',$id)->withTrashed()->forceDelete();
		  $success="Enquiry Permanently Deleted Successfully";
	return Redirect::route('enquiry/deletedfeedback')->with('success', $success);
	}



//========================================Contact Us Specialization TRASH====================================================
		
		public function contactusenquiry_listDeletedtrash()
		{ 
			$request_url_user = 'ContactUs Enquiry Trash';
						$PARENT_ID = 156;
			return view('admin.contactusenquiry_trash.deletelist',compact('PARENT_ID','request_url_user'));
			
		}
		public function contactusenquiry_listDeletedData(){
			// $enquiry = Contactus::select(['id','specialization_title','specialization_icon',DB::raw("DATE_FORMAT(created_at, '%d %M  %Y') as add_date")])->orderBy('id', 'desc')->onlyTrashed()->get();
			
           	$enquiry = Contactus::select('*',DB::raw("DATE_FORMAT(created_at, '%d %M  %Y') as add_date"))
		->onlyTrashed()
		->orderBy('enq_id', 'desc');


			return Datatables::of($enquiry)
			->addColumn('actions','<div class="btn-group">
			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/contactusenquiry_trash/$enq_id/confirm-restore")}}" class="btn btn-small btn-default"title="Restore"><i class="fa fa-undo"> Restore</i></a>
			<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/contactusenquiry_trash/$enq_id/final-delete")}}" class="delval btn btn-small btn-danger" title="Permanent Delete"><i class="fa fa-trash"> Delete</i></a></div>
			') ->rawColumns(['actions'])->make(true);
		}
		
		
		public function contactusenquiry_getModalRestore($id = null)
		{
			$model = 'Trash Record';
			$confirm_route = $error = null;
			$confirm_route = route('restore/contactusenquiry_trash', ['id' => $id]);
			return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
		public function contactusenquiry_restoreDeletedtrash($enu_id)
		{
			Contactus::withTrashed()->where('enq_id',$enu_id)->restore();
			$success="Trash Record Restore Successfully";
			return Redirect::route('contactusenquiry_trash/deletedfeedback')->with('success', $success);
		}
		
		public function contactusenquiry_getModalFinalDelete($id = null)
		{
			$model = 'Trash Record';
			$confirm_route = $error = null;
			$confirm_route = route('finaldelete/contactusenquiry_trash', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
		public function contactusenquiry_permanentDelete($id)
		{
			
			$detail=Contactus::select('*') ->where('enq_id',$id)->onlyTrashed()->first();
			
			// echo($detail->profile_photo);die;
			
			if(isset($detail->profile_photo))
			{
				$folderName = '/contactusenquiry';
				$filedir = $folderName .'/'. $detail->profile_photo;
				$query=Storage::disk('uploads')->delete($filedir);
				// echo($filedir);die;
				
			}
			Contactus::where('enq_id',$id)->withTrashed()->forceDelete();
			$success="Trash Record Permanently Deleted Successfully";
			return Redirect::route('contactusenquiry_trash/deletedfeedback')->with('success', $success);
		}
		
	}

