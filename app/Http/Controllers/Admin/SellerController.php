<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request as valRquest;
use App\Http\Requests;
use App\Http\Requests\SellerPassRequest;
use App\Http\Requests\SellerStatusRequest;
use Validator;
use File;
use Hash;
use Illuminate\Support\Facades\Request;
use Lang;
use Mail;
use Redirect;
use Sentinel;
use URL;
use View;
use App\User;
use App\SellerDetails;
use App\Notification;
use App\BmgServices;
use App\UserService;
use App\RoleUser;
use App\Products;
use App\Order;
use App\State;
use Session;
use DB;
use Carbon\Carbon;
use Response;
use Input;
use App\Helpers\datehelper;
use Storage;


class SellerController extends CodespurController
{

    function __construct()
     {
		 $this->date=datehelper::dateformat();
          parent::__construct();
	      if (Sentinel::check())
            {
               $this->userId = Sentinel::getUser()->id;
            }
     }

   //*********************** admin panel functions ************************//
	
	public function register()
	{
		//echo "done"; die;
		$state = State::orderby('st_name','asc')->get();
		
		return view('admin.seller.register', compact('state')); 
	}
	
	public function postRegister(valRquest $request)
	{
		//~ echo "done"; 
		//~ echo date('Y-m-d');
		//~ die;
		  
         $rules['first_name']			= "required|min:3";
         $rules['last_name']			= "required|min:3";
         $rules['email']				= "required|email|unique:users,email";
         $rules['mobileno']				= "required|numeric|unique:users,mobileno";
         $rules['password']				= "required|between:3,32";
         $rules['password_confirm']		= "required|same:password";
         $rules['company_name']			= "required|min:3";
         $rules['category']				= "required";
         $rules['city']					= "required";
         $rules['state']				= "required";
         $rules['company_telephone']	= "required";
         $rules['postcode']				= "required";
         $rules['address']				= "required";
         $rules['position']			= "required";
         //$rules['store_name']			= "required|min:6|max:25|regex:/^[a-zA-Z0-9\-]+$/";
         $rules['store_name']			= "required|max:25";
         $rules['store_phone_number']	= "required";
		 $rules['shop_logo'] 			= "required|mimes:jpg,jpeg,png";
		 $rules['location_on_map'] 		= "required";
		
		$errorMsg		= "Opps ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		else
		{			
			$UserData = User::where('email',$request->get('email'))->first();

			if(empty($UserData))
			{
				$register = array(
						'first_name' => $request->get('first_name'),
						'last_name' =>$request->get('last_name'),
						'email' => $request->get('email'),
						'mobileno' => $request->get('mobileno'),
						'password' => $request->get('password') ,
				);

				$activate = true;

				$user = Sentinel::register( $register, $activate);
				
				$userdata = Sentinel::findById($user->id);

				//Sentinel::login($userdata);
				  //\Session::save();
					
				$role = Sentinel::findRoleByName('Seller');
				
				$checkRole = RoleUser::where('user_id',$user->id)->where('role_id',$role->id)->first();
					
					
					
					if(empty($checkRole)){
						
						//die('okkhh');
						$roleObj = new RoleUser();
						$roleObj->user_id    = $user->id;
						$roleObj->role_id    = $role->id;

						$roleObj->save();
						
					}
							  
						  
				 	
				 if ($file = $request->file('shop_logo'))
					{
						$fileName = $file->getClientOriginalName();
						$extension = $file->getClientOriginalExtension();
						$folderName = '/seller/'.$user->id.'/';
						$safeName = str_random(10) . '.' . $extension;
						Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
						$project_image = $safeName;
						
						
				 }
					

				$seller = new SellerDetails();

				$seller->company_name        =  $request->get('company_name');
				$seller->category            =  $request->get('category');
				$seller->postcode            =  $request->get('postcode');

				$seller->city                =  $request->get('city');
				$seller->state               =  $request->get('state');
				$seller->company_telephone   =  $request->get('company_telephone');
				$seller->address             =  $request->get('address');
				$seller->user_id             =  $user->id;
				$seller->first_name          =  $request->get('first_name');
				$seller->last_name           =  $request->get('last_name');
				$seller->email               =  $request->get('email');
				$seller->mobileno            =  $request->get('mobileno');
				$seller->shop_name           =  $request->get('store_name');
				$seller->store_phone_number  =  $request->get('store_phone_number');
				
				$seller->location_on_map     =  $request->get('location_on_map');
				$seller->latitude            =  $request->get('latitude');
				$seller->longitude           =  $request->get('longitude');
				
				$seller->company_logo           =  $project_image;
				preg_match("/[^\.\/]+\.[^\.\/]+$/", basename(URL::to('/')), $matches);
				if(isset($matches[0]))
				$seller->shop_url            = strtolower($seller->shop_name.'.'.$matches[0]);
				
				$seller->status		 		 =	"Approve";
				$seller->approve_date		 =	date('Y-m-d');
				$seller->designation         =  $request->get('position');
				$seller->save();

				$bmgservice = BmgServices::select('srv_id')->where('slug','dolovery- seller')->first();
				$userservice = new UserService();
				$userservice->member_id  =  $user->id;
				$userservice->service_id  = $bmgservice->srv_id;
				$userservice->agree_terms  = "Yes";
				$userservice->save();

				//return json_encode(array('status'=>"success",'redirect'=>true,'message'=>'Succesfully Submit','url'=>route('seller.term-condition')));
			
			
				$message = "Thank-You! Seller Added Successfully.";
				
				$output['status']			= 'success';
				$output['success_msg']		= $message;
				$output['msg']				= $message;
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['success']			= true;
				$output['slideToTop']		= true;
				$output['url']				= route('sellers');
				
				return response()->json($output);
				
			
			}else{
					
				  $errorMsg = "Already have account";
				
				return response()->json(['error_msg'=>$errorMsg,'slideToTop'=>'yes']);
						
			}
		
		}
	}


	public function edit($id)
	{
		//echo "done"; die;
		$UserData = User::where('id',$id)->first();
		
		if($UserData)
		{
			$sellerData = SellerDetails::where('user_id',$id)->first();
		}
		
		$state = State::orderby('st_name','asc')->get();
		
		return view('admin.seller.edit', compact('state','sellerData')); 
	}
	
	public function updateSeller(valRquest $request, $id)
	{
	
		$rules['first_name']			= "required|min:3";
		$rules['last_name']			= "required|min:3";
		$rules['company_name']			= "required|min:3";
		$rules['city']					= "required";
		$rules['state']				= "required";
		$rules['company_telephone']	= "required";
		$rules['postcode']				= "required";
		$rules['address']				= "required";
		$rules['position']			= "required";
		$rules['store_name']			= "required|min:6|max:25";
		$rules['store_phone_number']	= "required";

		if ($request->file('shop_logo'))
		{
			$rules['shop_logo'] 			= "mimes:jpg,jpeg,png";
		}
			
		$rules['location_on_map'] 		= "required";
		
		$errorMsg		= "Opps ! Please fill required fields.";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray'=>$validator->errors(),'error_msg'=>$errorMsg,'slideToTop'=>'yes']);
		}
		else
		{			
				User::where('id',$id)->update(['first_name'=>$request->get('first_name'),'last_name'=>$request->get('last_name')]);
		  
				 
				$record = SellerDetails::select('company_logo')->where('user_id',$id)->first();
				
				if ($file = $request->file('shop_logo'))
				{				
					$fileName = $file->getClientOriginalName();
					$extension = $file->getClientOriginalExtension();
					$folderName = '/seller/'.$id.'/';
					$safeName = str_random(10) . '.' . $extension;
					Storage::disk('uploads')->putFileAs($folderName, $file,$safeName);
					$project_image = $safeName;
					
					 if(count($record)>0 && ($record->company_logo!='') && ($request->file('shop_logo')!=''))
					{
						$folderName = '/seller/'.$id.'/';
						$filedir = $folderName .'/'. $record->company_logo;
						Storage::disk('uploads')->delete($filedir);
					}				
				}
				else
				{
					$project_image = $record->company_logo;
				}
			
				
				SellerDetails::where('user_id',$id)->update([
				
					'company_name'        =>  $request->get('company_name'),
					'postcode'            =>  $request->get('postcode'),
					'city'                =>  $request->get('city'),
					'state'               =>  $request->get('state'),
					'company_telephone'   =>  $request->get('company_telephone'),
					'address'             =>  $request->get('address'),
					'first_name'          =>  $request->get('first_name'),
					'last_name'           =>  $request->get('last_name'),
					'shop_name'           =>  $request->get('store_name'),
					'store_phone_number'  =>  $request->get('store_phone_number'),
					'location_on_map'     =>  $request->get('location_on_map'),
					'latitude'            =>  $request->get('latitude'),
					'longitude'           =>  $request->get('longitude'),
					'company_logo'        =>  $project_image,
					'designation'         =>  $request->get('position')
				
				]);

			

                
				$message = "Thank-You! Seller Updated Successfully.";
				
				$output['status']			= 'success';
				$output['success_msg']		= $message;
				$output['msg']				= $message;
				$output['msgHead']			= "Success ! ";
				$output['msgType']			= "success";
				$output['success']			= true;
				$output['slideToTop']		= true;
				$output['url']				= route('sellers');
				
				return response()->json($output);
				
		}
	}

	
	
   public function sellerIndex()
   {
	   $PARENT_ID = 63;
	   $users = User::join('role_users','role_users.user_id','=','users.id')
	                    ->join('roles','roles.id','=','role_users.role_id')
	                    ->join('seller_details','seller_details.user_id','=','users.id')
	                    ->leftjoin('category','category.id','seller_details.category')
	                     ->select('users.id','category.category_name','users.email','users.created_at as registr_date','seller_details.company_name','seller_details.company_telephone','seller_details.status','seller_details.created_at as regdate',DB::raw("(DATE_FORMAT(`tbl_seller_details`.created_at,'%d %M  %Y')) as add_date"))
	                     ->where('roles.name','Seller')
	                     ->where('seller_details.status','!=', 'Inactive')
	                     ->orderBy('seller_details.user_id' ,"DESC")
	                     ->get();

	    return view('admin.seller.index', compact('users','PARENT_ID'));

	 }


      public function approve(SellerStatusRequest $request, $company)
      {

		 $seller = SellerDetails::where('slug',$company)->first();

		 $user = Sentinel::findById($seller->user_id);


         if(!empty($seller))
         {

				$datenow =   Carbon::now();

				$sellerap['status'] = $request->status;
				$sellerap['auto_approval'] = $request->auto_approval;
				$sellerap['commission'] = 0;
				$sellerap['approve_date'] =  $datenow;

			    $seller_company = SellerDetails::updateOrCreate(['user_id' => $seller->user_id], $sellerap);

			  //~ $noti = new Notification();
			  //~ $noti->user_id   =    $seller->user_id;
			  //~ $noti->message   = $request->get('message');
			  //~ $noti->type      = "seller";

			  //~ $noti->save();


			 $data = array(
			 'user'    =>  $user->first_name,
			 'useremail'    =>  $user->email,
	     	 'status'    => $request->status,
             'message'  => $request->get('message'),

                   );

	      }
		else
		{
			return Response::view('404', array(), 404);
		}


			return(json_encode(
				array(
				'success' => true,
				'status' => "success"

				)));


	  }

	  public function changePassword(SellerPassRequest $request,$company)
	  {
	    if (Request::ajax()) {

            $seller = SellerDetails::where('slug',$company)->first();


            $user = Sentinel::findById($seller->user_id);



            $password = Request::get('password');

            $user->password = Hash::make($password);
            $user->save();


            return(json_encode(
				array(
				'success' => true,
				'status' => "success"

				)));

        }


		  }
   public function sellerShow($id)
    {
    
   
    
        try {
            // Get the user information
            $user = Sentinel::findUserById($id);

            //
            if( empty($user))
            {
				//echo "sas"; die;
				 $error = Lang::get('Seller does not exist.', compact('id'));
                      return Redirect::route('sellers')->with('error', $error);

				}
			$sellerdetails = SellerDetails::where('user_id',$user->id)->first();
			if(empty($sellerdetails)){

					 $error = Lang::get('Seller does not exist. ', compact('id'));
                      return Redirect::route('sellers')->with('error', $error);


					} 

            $seller = SellerDetails::select('seller_details.*','state.st_name','category.category_name')
				->leftjoin('state','state.state_id','=','seller_details.state')
				->leftjoin('category','category.id','seller_details.category')
				->where('user_id', $user->id)->first();
				
				$noOfProduct = Products::where('seller_id',$user->id)->count();

		$noOfOrder = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$user->id)
	->groupby('order.order_id')
	->count();
	
	$noOfOrderCancel = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$user->id)
	->where('order_item.order_status','Item not available')
	->orWhere('order_item.order_status','failed')
	->groupby('order.order_id')
	->count();
	
	$noOfOrderShipPending = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$user->id)
	->where('order_item.order_status','Item Ready to Ship')
	->groupby('order.order_id')
	->count();
	
	$noOfOrderShip = Order::
	 Join('order_item','order_item.order_id','=','order.order_id')
	->where('order_item.seller_id',$user->id)
	->where('order_item.order_status','order shipped')
	->groupby('order.order_id')
	->count();
	
	
	
	
				
            //get country name
            
           // $abc = SellerDetails::leftjoin('state','state.state_id','=','seller_details.state')->where('user_id', $user->id)->first();
 

        } catch (UserNotFoundException $e) {
            // Prepare the error message
            $error = Lang::get('users/message.user_not_found', compact('id'));

            // Redirect to the user management page
            return Redirect::route('admin.users.index')->with('error', $error);
        }
		
		
		
		//echo "<pre>"; print_r($seller); die;

        // Show the page
        
        $PARENT_ID = 63;
        
        return View('admin.seller.show', compact('noOfOrderShip','noOfOrderShipPending','noOfOrderCancel','user','seller','selleDetails','PARENT_ID','noOfProduct','noOfOrder'));

    }
    
    	public function delete_modal($id = null)
    {
	
		$model = 'Seller';
		$confirm_route = $error = null;
		 $seller = SellerDetails::where('user_id',$id)->first();
		if (empty($seller)) {
			$error = 'Seller Not Found';
		}
		else
		{
			$confirm_route = route('admin.seller.delete', ['id' => $id]);
			return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
		}
    }
    
    public function delete_seller($id)
	{
		
		$store =  SellerDetails::where('user_id', $id)
					->update(['status' => 'Inactive']);	 
						

		$message="Success! Seller Deleted Successfully";
		$notification = array(
			
			'message' =>  $message, 
			'alert-type' => 'success'
			);
		return redirect('admin/sellers')->with($notification);
		
	}
	
		//========================================products TRASH====================================================
		
	public function listDeletedtrash()
	{ 
		$request_url_user = 'Sellers Trash';
		$PARENT_ID = 51;

		$PARENT_ID = 51;
	   $users = User::join('role_users','role_users.user_id','=','users.id')
	                    ->join('roles','roles.id','=','role_users.role_id')
	                    ->join('seller_details','seller_details.user_id','=','users.id')
	                    ->leftjoin('category','category.id','seller_details.category')
	                     ->select('users.id','category.category_name','users.email','users.created_at as registr_date','seller_details.company_name','seller_details.company_telephone','seller_details.status','seller_details.created_at as regdate',DB::raw("(DATE_FORMAT(`tbl_seller_details`.created_at,'%d %M  %Y')) as add_date"))
	                     ->where('roles.name','Seller')
	                     ->Where('seller_details.status', 'Inactive')
	                     ->get();

	    return view('admin.seller.deletedlist', compact('users','PARENT_ID'));
	}

	public function listDeletedData(){
		$enquiry =  User::join('role_users','role_users.user_id','=','users.id')
                    ->join('roles','roles.id','=','role_users.role_id')
                    ->join('seller_details','seller_details.user_id','=','users.id')
                    ->leftjoin('category','category.id','seller_details.category')
                     ->select('users.id','category.category_name','users.email','users.created_at as registr_date','seller_details.company_name','seller_details.company_telephone','seller_details.status','seller_details.created_at as regdate',DB::raw("(DATE_FORMAT(`tbl_seller_details`.created_at,'%d %M  %Y')) as add_date"))
                     ->where('roles.name','Seller')
                     ->orWhere('seller_details.status', 'Inactive')
                     ->get();

		return Datatables::of($enquiry)
		->addColumn('actions','<div class="btn-group">
		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/sellers_trash/$id/confirm-restore")}}" class="btn btn-small btn-default"title="Restore"><i class="fa fa-undo"> Restore</i></a>
		<a data-toggle="modal" data-target="#modal-large" href="{{URL::to("admin/sellers_trash/$id/final-delete")}}" class="delval btn btn-small btn-danger" title="Permanent Delete"><i class="fa fa-trash"> Delete</i></a></div>
		') ->rawColumns(['actions'])->make(true);
	}
	
	
	public function getModalRestore($id = null)
	{
		$model = 'Sellers Trash Record';
		$confirm_route = $error = null;
		$confirm_route = route('restore/sellers_trash', ['id' => $id]);
		return View('admin/layouts/restore_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function restoreDeletedtrash($enu_id)
	{ 
		SellerDetails::where('user_id', $enu_id)->first()->update(['status' => 'Active']);
		$success="Trash Record Restore Successfully";
		return Redirect::route('sellers_trash/deletedfeedback')->with('success', $success);
	}
	
	public function getModalFinalDelete($id = null)
	{
		$model = 'Sellers Trash Record';
		$confirm_route = $error = null;
		$confirm_route = route('finaldelete/sellers_trash', ['id' => $id]);
		return View('admin/layouts/delete_modal_confirmation', compact('error', 'model', 'confirm_route'));
	}
	public function permanentDelete($id)
	{
		$detail=SellerDetails::where('id',$id)->first();
		// if(empty($detail))
		//   $detail = Products::where('id',$id)->where('status', 'Inactive')->first();
		if(isset($detail->company_logo))
		{
			$folderName = '/seller';
			$filedir = $folderName.'/'.$detail->company_logo;
			$query=Storage::disk('uploads')->delete($filedir);
		}

		SellerDetails::where('id',$id)->withTrashed()->forceDelete();
		$success="Trash Record Permanently Deleted Successfully";
		return Redirect::route('sellers_trash/deletedfeedback')->with('success', $success);
	}
   
}
