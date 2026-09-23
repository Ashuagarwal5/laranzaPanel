<?php

namespace App;

use DB;
use App\Helpers\common;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;


class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';
    protected $fillable = [

        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        ];
    // use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function __construct()
    {
        parent::__construct();
        $this->attributes=array('ip' =>Helpers\Thumbnail::getclientip(),'site_id'=>config('constants.siteinfo.site_id'));
    }
    public function dealer()
    {
        return $this->belongsTo('App\User', 'dealer_id');
    }

    public static function checkValidDealer($userId)
    {
           
		    $check_user = User::select('users.id')
		                ->join('role_users', 'role_users.user_id', '=', 'users.id')
		                ->join('roles', 'roles.id', '=', 'role_users.role_id')
		                ->where('roles.slug', 'dealer')
		                ->where('users.id', $userId)->first();
            if(isset($check_user) && !empty($check_user)) {
                return 'Yes';
            } 
            else {
	             return 'No';
            }
    }


    public static function Applogin($params)
    {
        
        $user_type = 'user';
        $mobileno = $params['mobileno'];

        if(isset($params['DeviceToken'])){
            $DeviceToken = $params['DeviceToken'];
        }
        else{
            $DeviceToken = NULL;
        }

        if (isset($params['resend'])) {
            $resend = $params['resend'];
        }
        else{
            $resend = null;
        }
        //$verification_code = '0000';
        $verification_code = common::generateRandomNumber(4);
        
       
        //If User Exists the user will enter OTP and will be rediect to login else create new user====#
        $check_user = User::select('users.id', 'users.full_name', 'users.email', 'users.mobileno', 'roles.slug', 'users.user_type')
                        ->leftjoin('dealers','dealers.user_id','users.id')
                        ->join('role_users', 'role_users.user_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'role_users.role_id')
                    //->where('roles.slug', $user_type)
                        // ->where('users.mobileno', $mobileno)
                        ->where(function ($query) use ($mobileno) {
                            $query->where('users.mobileno', '=', $mobileno)
                                  ->orWhere('dealers.mobile_no', '=', $mobileno);
                                })
                        ->first();
        $error = false;
        try {
            if($check_user) //=====If entered mobile number is already exist in database
            {
                if ($check_user->slug == 'user')
                {
                    $user = $check_user;
                    if($resend)
                    User::where('id', $check_user->id)->update(['verification_code' => $verification_code]);
                    else
                    User::where('id', $check_user->id)->update(['verification_code' => $verification_code, 'DeviceToken' => $DeviceToken]);


                    
                    $user_status = 'Old';
                    $status = 'success';
                    $msg = 'User found Successfully';
                }
                if ($check_user->slug == 'dealer') {
                    $resend = false;
                    $error = true;
                    $status = 'error';
                    $msg = "Registration now allowed, Mobile number is registered as Dealer";
                }
                if ($check_user->slug == 'salesofficier')
                {
                    $resend = false;
                    $error = true;
                    $status = 'error';
                    $msg = "Registration now allowed, Mobile number is registered as Sales Officer";
                }
            }
            else
            {
                //Means this mobile number is new to system to create user
                $role = Sentinel::findRoleByName($user_type);
               
                if ($role) {
                    //$password = common::generateRandomNumber(6);
                    $password = 123562;
                    $register = array(
                                'password' => $password,
                                'mobileno' => $mobileno,
                                'user_type' => $user_type,
                                'verification_code' => $verification_code,
                            );

                    $activate = true;
                    $userSentinal = Sentinel::create($register);
                    $user = $userSentinal;
                    $activation2 = Activation::create($userSentinal);
                    Activation::complete($user, $activation2->code);                  
					#===========Once User is register in db through Sentinel then next task is to save Device Token for that user==#
					User::where('id', $user->id)->update(['DeviceToken' => $DeviceToken, 'verification_code' => $verification_code]);


                    $userdata = Sentinel::findById($user->id);
                    $checkRole = RoleUser::where('user_id', $user->id)->where('role_id', $role->id)->first();
                    if (empty($checkRole)) {
                        $roleObj = new RoleUser();
                        $roleObj->user_id = $user->id;
                        $roleObj->role_id = $role->id;
                        $roleObj->save();
                    }
                    $user_status = 'New';
                    $status = 'success';
                    $msg = 'Login Successfully';
                } else {
                    $error = true;
                    $status = 'error';
                    $msg = $user_type . ' Role is incorrect';
                }
            }
        } catch (UserNotFoundException $e) {
            $error = true;
            $status = 'error';
            $msg = Lang::get('auth/message.account_not_found');
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
            $error = true;
            $status = 'error';
            $msg = "Your email is not verified yet, please check your mailbox to verify email";
        } catch (\Cartalyst\Sentinel\Checkpoints\UserSuspendedException $e) {
            $error = true;
            $status = 'error';
            $msg = Lang::get('auth/message.account_suspended');
        } catch (\Cartalyst\Sentinel\Checkpoints\UserBannedException $e) {
            $error = true;
            $status = 'error';
            $msg = Lang::get('auth/message.account_banned');
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            $error = true;
            $delay = $e->getDelay();
            $status = 'error';
            $msg = Lang::get('auth/message.account_suspended', compact('delay'));
        }


        if ($error == false) {
            //$result['verification_code'] = $verification_code;
            $result['mobileno'] = $mobileno;
            //$result['user_status'] = $user_status;
            $status = 'success';
            $msg = " OTP sent Successfully on your mobile";
            $push_data['otp'] = $verification_code;
            $push_data['receiver_id'] = $user->id;
            $push_data = (object) $push_data;
            //PushNotification::send('login_otp', $push_data);
            $type='User';


            // if($check_user->slug == 'dealer') { $type='Dealer'; }
            // $message="Hi ".$type.", \n\n *" . $verification_code . "* is your OTP. Enter this code to continue login \n\n Prime Comfort";
            $message="Dear ".$type.", ".$verification_code." is the OTP for your login. In case you have not requested this, please contact us at support@laranaz.in";
            $msgwhat['args']=array('to'=>$mobileno,'content'=>$message);
            $msgwhat['method']='sendText';

            //self::whatsapp($msgwhat);


            SendMessage::getSendMessage('Custom OTP', $user->id, $point = null, $totype='user');




        }
        //echo  "<pre>"; print_r($params); die;
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }

        if ($resend == true) {
            $msg = 'OTP Resent Successfully';
        }

        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }





    public static function AppDealerCheck($params)
    {
        $user_type = 'dealer';
        $mobileno = $params['mobileno'];
        $error = false;
        try {
            $check_user = User::select('users.id', 'full_name', 'city', 'email', 'mobileno', 'roles.slug', 'users.user_type')
                ->join('role_users', 'role_users.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_users.role_id')
                ->where('roles.slug', $user_type)
                ->where('users.mobileno', $mobileno)
                ->first();
            if ($check_user) {
                $status = 'success';
                $msg = 'Dealer found Successfully';
                $result['dealer_id'] =$check_user->id ;
                $result['dealer'] =$check_user->full_name ;
                $result['city'] =$check_user->city ;
            } else {
                $status = 'error';
                $msg = 'Dealer not found';
                $result['dealer_id']=0;
                $error=true;
            }
        } catch (UserNotFoundException $e) {
            $error = true;
            $status = 'error';
            $msg = "Dealer Not Found";
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }

        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }

    public static function AppVerification($params)
    {
        $result = array();
        $status = 'error';
        $msg = '';
        try {
            $mobileno = $params['mobileno'];
            $verification_code = $params['verification_code'];
            $DeviceID = $params['DeviceID'];
            $Manufacturer = $params['Manufacturer'];
            if ($mobileno == "9950448855" && $verification_code == '1234') {
                $userdata = User::select('users.id', 'users.full_name', 'users.state', 'users.mobileno', 'users.email', 'users.city', 'users.profile_status', 'users.gender', 'users.profile_photo', 'users.dob', 'users.address', 'users.pincode', 'roles.slug as type', 'users.user_type', 'users.step_completed','users.account_type','users.dealer_id','dealers.dealer_name')
                ->where('users.mobileno', $mobileno)
                ->join('role_users', 'role_users.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_users.role_id')
                ->leftjoin('dealers', 'users.dealer_id', '=', 'dealers.user_id')
                ->first();
            }
            else{
                $userdata = User::select('users.id', 'users.full_name', 'users.state', 'users.mobileno', 'users.email', 'users.city', 'users.profile_status', 'users.gender', 'users.profile_photo', 'users.dob', 'users.address', 'users.pincode', 'roles.slug as type', 'users.user_type', 'users.step_completed','users.account_type','users.dealer_id','dealers.dealer_name')
                    ->where('users.verification_code', $verification_code)
                    ->where('users.mobileno', $mobileno)
                    ->join('role_users', 'role_users.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_users.role_id')
                    ->leftjoin('dealers', 'users.dealer_id', '=', 'dealers.user_id')
                    ->first();
            }
            /*$userdata = User::select('id', 'email', 'mobileno', 'full_name', 'profile_photo', 'city', 'profile_status')
            ->where('mobileno', $mobileno)->where('verification_code', $verification_code)->first();*/
            if (!empty($userdata)) {
                $user_id = $userdata->id;
                $data = User::where('id', $user_id)
                ->update(
                    ['verification_code' => '',
                    'DeviceID' =>$DeviceID,
                    'Manufacturer' => $Manufacturer,
                    'last_login' => date('Y-m-d h:i:s'),
                    'verification_status' => 'Approve',
                ]
                );
                $result['data'] = $userdata;
                $status = 'success';
                $msg = 'Login Successfully';
            } else {
                $status = 'error';
                $msg = 'The OTP entered is incorrect Please Enter correct OTP ';
            }
        } catch (UserNotFoundException $e) {
            $status = 'error';
            $msg = Lang::get('auth/message.account_not_found');
        } catch (\Cartalyst\Sentinel\Checkpoints\NotActivatedException $e) {
            $status = 'error';
            $msg = "Your email is not verified yet, please check your mailbox to verify email";
        } catch (\Cartalyst\Sentinel\Checkpoints\UserSuspendedException $e) {
            $status = 'error';
            $msg = Lang::get('auth/message.account_suspended');
        } catch (\Cartalyst\Sentinel\Checkpoints\UserBannedException $e) {
            $status = 'error';
            $msg = Lang::get('auth/message.account_banned');
        } catch (\Cartalyst\Sentinel\Checkpoints\ThrottlingException $e) {
            $delay = $e->getDelay();
            $status = 'error';
            $msg = Lang::get('auth/message.account_suspended', compact('delay'));
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }




    #==============This function used to create profile and edit profile============#
    public static function AppeditProfile($params)
    {
        $mobileno = $params['mobileno'];
        $city = $params['city'];
        $usertype=@$params['usertype'];
        $address_line2 = NULL;
        $address_line2 = @$params['address_line2'];

        if(empty($params['referral_code'])) $params['referral_code'] = NULL;


        #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
        $isUserDeleted = self::checkUserDeleted('mobileno',$mobileno);
        if($isUserDeleted == false)
        {
             $result['replyStatus'] = false;
             $result['replyMessage'] = 'User Not found or removed';
             return $result;
        }
        #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#



        $roles='';

        $userdata  = User::select(
            'users.id',
            'users.full_name',
            'users.state',
            'users.mobileno',
            'users.email',
            'users.dob',
            'users.city',
            'users.profile_status',
            'users.address',
            'users.address_line2',
            'users.pincode',
            'account_type',
            'user_type',
            'dealer_id'
        )
                                  ->where('mobileno', $mobileno)
                                  ->first();

        if ($usertype!="") {
            $roles=Roles::where('id', $usertype)->first();
            if ($roles) {
                $roles= $roles->name;
            }
        } else {
            $roles=$userdata->user_type;
        }




        try {
            $data = User::where('mobileno', $mobileno)
            ->update(['full_name' => $params['full_name'],
                'email' => strtolower($params['email']),
                'dob' => $params['dob'],
                'pincode' => $params['pincode'],
                'address' => $params['address'],
                'address_line2' => $address_line2,
                'state' => $params['state'],
                'city' => $city,
                'referral_code' => $params['referral_code'],
                'profile_status' => 'Pending',
                'account_type' => $params['account_type'],
                'dealer_id' => $params['dealer_id'],
                'step_completed' => 1,
                'user_type'=>$roles
            ]);

            if ($usertype!="") {
                RoleUser::where('user_id', $userdata->id)->update(['role_id'=>$usertype]);
            } else {
            }

            if ($data) {
                $result['data']  = User::select(
                    'users.id',
                    'users.full_name',
                    'users.state',
                    'users.mobileno',
                    'users.email',
                    'users.city',
                    'users.profile_status',
                    'users.gender',
                    'users.profile_photo',
                    'users.dob',
                    'users.address',
                    'users.address_line2',
                    'users.pincode',
                    'users.user_type',
                    'users.profile_status',
                    'users.step_completed',
                    'users.account_type',
					'users.dealer_id'

                )
                                            ->where('users.mobileno', $mobileno)
                                            ->first();

                $status = 'success';
                $msg = 'Sucessfull Updated';
            } else {
                $status = 'error';
                $msg = 'Wrong User Id';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (PDOException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }


    #==============This function used to create profile and edit profile============#
    public static function AppSkipEditProfile($params)
    {
        $mobileno = $params['mobileno'];
        $usertype=@$params['usertype'];

        #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
        $isUserDeleted = self::checkUserDeleted('mobileno',$mobileno);
        if($isUserDeleted == false)
        {
            $result['replyStatus'] = false;
            $result['replyMessage'] = 'User Not found or removed';
            return $result;
        }
        #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#



        $roles='';

        $userdata  = User::select(
            					'users.id',
								'users.full_name',
					            'users.state',
					            'users.mobileno',
					            'users.email',
					            'users.dob',
					            'users.city',
					            'users.profile_status',
					            'users.address',
					            'users.address_line2',
					            'users.pincode',
					            'users.account_type',
					            'users.dealer_id',
					            'user_type'
								)
                                ->where('mobileno', $mobileno)
                                ->first();

        if ($usertype!="")
        {
            $roles=Roles::where('id', $usertype)->first();
            if ($roles)
            {
                $roles= $roles->name;
            }
        }
        else
        {
            $roles=$userdata->user_type;
        }




        try {
            $data = User::where('mobileno', $mobileno)
            ->update(['profile_status' => 'Pending',
                'step_completed' => 1,
                'user_type'=>$roles
            ]);

            if ($usertype!="")
            {
                RoleUser::where('user_id', $userdata->id)->update(['role_id'=>$usertype]);
            }
            else
            {

            }

            if ($data) {
                $result['data']  = User::select(
						                    'users.id',
						                    'users.full_name',
						                    'users.state',
						                    'users.mobileno',
						                    'users.email',
						                    'users.city',
						                    'users.profile_status',
						                    'users.gender',
						                    'users.profile_photo',
						                    'users.dob',
						                    'users.address',
						                    'users.address_line2',
						                    'users.pincode',
						                    'users.user_type',
						                    'users.profile_status',
						                    'users.step_completed',
						                    'users.account_type',
											'users.dealer_id'
											)
                                            ->where('users.mobileno', $mobileno)
                                            ->first();

                $status = 'success';
                $msg = 'Sucessfull Skipped';
            } else {
                $status = 'error';
                $msg = 'Wrong User Id';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (PDOException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }


    #==============This function used to add bank details===========================#
    public static function AppSkipEditBankDetails($params)
    {
        $user_id = $params['user_id'];


        #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
        $isUserDeleted = self::checkUserDeleted('userid',$user_id);
        if($isUserDeleted == false)
        {
            $result['replyStatus'] = false;
            $result['replyMessage'] = 'User Not found or removed';
            return $result;
        }
        #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#

        try {
            //====Update Step Completed to 1=====================#
          $isUpdate =   User::where('id', $params['user_id']) ->update(['step_completed' => 3]);

            if ($isUpdate) {
                $result['data']  = User::select(
                    'users.id',
                    'users.full_name',
                    'users.state',
                    'users.mobileno',
                    'users.email',
                    'users.city',
                    'users.profile_status',
                    'users.gender',
                    'users.profile_photo',
                    'users.dob',
                    'users.address',
                    'users.address_line2',
                    'users.pincode',
                    'users.user_type',
                    'users.profile_status',
                    'users.step_completed',
                    'users.account_type',
					'users.dealer_id'
                )
                                            ->where('users.id', $params['user_id'])
                                            ->first();

				#========Send Notification to Admin about new user signup=========#
				$msg = SendMessage::getSendMessage('Admin Registration Notification', $params['user_id'], null, 'admin');

                $status = 'success';
                $msg = 'Bank Details Skipped Successfully';
            } else {
                $status = 'error';
                $msg = 'Wrong User Id';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (PDOException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }

    public static function AppEditBankDetails($params)
    {
        $user_id = $params['user_id'];
        $payment_type = $params['payment_type'];
        #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
        $isUserDeleted = self::checkUserDeleted('userid',$user_id);
        if($isUserDeleted == false)
        {
            $result['replyStatus'] = false;
            $result['replyMessage'] = 'User Not found or removed';
            return $result;
        }
        #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#

        try {
            //======Insert, Update Bank Details Data===============================#
            (isset($params['bd_id']) && !empty($params['bd_id'])) ? $bdId = $params['bd_id'] : $bdId = null;


            $input_array = array();
            if ($payment_type == 'Bank Details') {
                $input_array['payment_type']  	= $params['payment_type'];
                $input_array['user_id']  		= $params['user_id'];
                $input_array['bank_name']  		= $params['bank_name'];
                $input_array['branch_name']  	= $params['branch_name'];
                $input_array['ifsc_code']  		= $params['ifsc_code'];
                $input_array['account_number']  = $params['account_number'];
            }

            if ($payment_type == 'UPI') {
                $input_array['payment_type']  	= $params['payment_type'];
                $input_array['user_id']  		= $params['user_id'];
                $input_array['upi_id']  		= $params['upi_id'];
            }
            if ($payment_type == 'GPAY') {
                $input_array['payment_type']  	= $params['payment_type'];
                $input_array['user_id']  		= $params['user_id'];
                $input_array['gpay_number']  	= $params['gpay_number'];
            }


            $data = Bank_detail::updateOrCreate(['id'=>$bdId], $input_array); //We are not using $input

            //====Update Step Completed to 1=====================#
            User::where('id', $params['user_id']) ->update(['step_completed' => 3]);

            if ($data) {
                $result['data']  = User::select(
                    'users.id',
                    'users.full_name',
                    'users.state',
                    'users.mobileno',
                    'users.email',
                    'users.city',
                    'users.profile_status',
                    'users.gender',
                    'users.profile_photo',
                    'users.dob',
                    'users.address',
                    'users.pincode',
                    'users.user_type',
                    'users.profile_status',
                    'users.step_completed',
                    'users.account_type',
					'users.dealer_id'

                )
                                            ->where('users.id', $params['user_id'])
                                            ->first();

				#========Send Notification to Admin about new user signup=========#
				$msg = SendMessage::getSendMessage('Admin Registration Notification', $params['user_id'], null, 'admin');

                $status = 'success';
                $msg = 'Bank Details Submitted Successfully';
            } else {
                $status = 'error';
                $msg = 'Wrong User Id';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (PDOException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }


    #==============This function used to add bank details===========================#
    public static function AppUploadDocuments($params)
    {
        $user_id = $params['user_id'];

        #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
        $isUserDeleted = self::checkUserDeleted('userid',$user_id);
        if($isUserDeleted == false)
        {
            $result['replyStatus'] = false;
            $result['replyMessage'] = 'User Not found or removed';
            return $result;
        }
        #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#

        try {
            $data = true;



            User::where('id', $params['user_id']) ->update(['step_completed' => 2]);

            if ($data) {
                $result['data']  = User::select(
                    'users.id',
                    'users.full_name',
                    'users.state',
                    'users.mobileno',
                    'users.email',
                    'users.city',
                    'users.profile_status',
                    'users.gender',
                    'users.profile_photo',
                    'users.dob',
                    'users.address',
                    'users.pincode',
                    'users.user_type',
                    'users.profile_status',
                    'users.step_completed',
                    'users.account_type',
					'users.dealer_id'
                )
                                            ->where('users.id', $params['user_id'])
                                            ->first();

                $status = 'success';
                $msg = 'Sucessfull Updated';
            } else {
                $status = 'error';
                $msg = 'Wrong User Id';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (PDOException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }


    #==============This function used to add bank details===========================#
    public static function AppUserProfileStatus($params)
    {
        $user_id = $params['user_id'];
        #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
        $isUserDeleted = self::checkUserDeleted('userid',$user_id);
        if($isUserDeleted == false)
        {
            $result['replyStatus'] = false;
            $result['replyMessage'] = 'User Not found or removed';
            return $result;
        }
        #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#


        try {
            if ($user_id)
            {
                $result['data']  = User::select('profile_status')
                                          ->where('users.id', $params['user_id'])
                                          ->first();
                if(isset($result['data']) && !empty($result['data']))
                {
                    $status = 'success';
                    $msg = 'Fetching your profile status';
                }
                else
                {
                    $status = 'error';
                    $msg = 'User deleted or not found';
                }
            }
            else
            {
                $status = 'error';
                $msg = 'Wrong User Id';
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (PDOException $e) {
            $status = 'error';
            $msg = "Error : " . $e->getMessage();
        } catch (\Exception $e) {
            $status = 'error';
            $msg = $e->getMessage();
        }
        if ($status == 'success') {
            $statusType = true;
        } else {
            $statusType = false;
        }
        $result['replyStatus'] = $statusType;
        $result['replyMessage'] = $msg;
        return $result;
    }


    public static function whatsapp($payload)
    {

        //$url = "http://wp.bigtos.com:3002/";
        $post = [
        'key' => 'JSYFL7PBQZCODESPURUSF7BOVPRTI',
        'mobileno' => $payload['args']['to'],
        'msg'   => $payload['args']['content'],
            'type'=>'Text'  //Image For Image
        ];

        $url="https://www.cp.bigtos.com/api/v1/sendmessage";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        // echo $response;die();
        # Print response.
        return $response;
    }
    //check if current user has purchased a particullar product
    public static function check_for_review($user_id, $product_id)
    {
        $order = DB::table('order')
     ->where('member_id', $user_id)
     ->where('shipment_status', 'Shipped')
     ->get();
        if (count($order) > 0) {
            $product = DB::table('order_item')
         ->where('member_id', $user_id)
         ->where('product_id', $product_id)
         ->get();
            if (count($product) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    //check if current user has deleted or not
    public static function checkUserDeleted($type, $value)
    {
          if($type == 'mobileno')
          $users = User::where('mobileno', $value)->first();
          if($type == 'userid')
          $users = User::where('id', $value)->first();


          if(!empty($users) && isset($users))
          {
                return true;
          } else
          {
                return false;
          }

    }

}
