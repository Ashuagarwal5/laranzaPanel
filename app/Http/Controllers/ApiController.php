<?php
/*
Tradelit Infocom India Pvt. Ltdl
Umesh Naga
 */

namespace App\Http\Controllers;

use Cache;
use DB;
use App\User;
use Illuminate\Http\Request;
use Input;
use Response;
use Carbon\Carbon;
use App\States;
use App\City;
use App\Banner;
use App\WebsiteSetting;
use App\Pincode;
use App\Products;
use App\PointsRedeem;
use App\Infobox;
use App\CustomerPoints;
use App\RewardProduct;
use App\RewardClaim;
use App\Document;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\PhoneServiceProvider;
use Propaganistas\LaravelPhone\Rules\Phone as Rule;
use Illuminate\Support\Str;
use App\Notification;
use App\Dealer;

class ApiController extends Controller
{
    public $replyStatus = array('success', 'error');
    public $tablemember = 'users';
    public $verification_code = '';
    public $device = '';
    public $noimage = '';
    public $data;
    public function __construct()
    {
        //parent::__construct();
    }

    //** function Index **//


    #<=<=<=<=<=<=<=<=<=<=<=<=API For Settings & Configuratios<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function settings()
    {
        $response = array();
        $data = array();
        $data['baseurl'] = 'https://app.laranza.in';
        $data['imgurl']='https://ik.imagekit.io/jlf4pxogf/';
        $data['sharemsg']='Hey, Checkout  the Laranza App';
        $data['shareurl']='https://app.laranza.in';
        $data['apptitle']=WebsiteSetting::where('id', 1)->first()->site_name;
        $data['helplineno']=WebsiteSetting::where('id', 1)->first()->contact_no;
        $data['redeem_status']=WebsiteSetting::where('id', 1)->first()->redeem_status;
        $data['appversion']=WebsiteSetting::where('id', 1)->first()->app_version;
        $data['address']=WebsiteSetting::where('id', 1)->first()->address;
        $data['min_req_points']=WebsiteSetting::where('id', 1)->first()->min_req_points;


        $data['appurl']='https://play.google.com/store/apps/details?id=com.laranza';
        $data['currency']='₹';
        $data['customertype']=array(array('id'=>4,'name'=>'Dealer','name_hi'=>'डीलर','image'=>'dealer.png'),array('id'=>5,'name'=>'Sales Person','image'=>'salesperson.png','name_hi'=>'सेल्स पर्सन'),array('id'=>1,'name'=>'Customer/Kariger','image'=>'carpenter.png','name_hi'=>'ग्राहक/कारीगर'),array('id'=>6,'name'=>'Architect/Interior Designer ','image'=>'interiordesigne.png','name_hi'=>'आर्किटेक्ट या इंटीरियर डिज़ाइनर'));

        $response['data'] = $data;
        $response['replyStatus'] = true;
        $response['replyMessage'] = '';
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#

    #<=<=<=<=<=<=<=<=<=<=<=<=API For Login<=<=<=<=<=<=<=<=<=<=<<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function login(Request $request)
    {
        $input = Input::all();
        $data=array();
        if(isset($input['resend']))	//In Resend we are not sending Device Token
        {
        	$rules['mobileno'] 		= 'required|numeric|digits:10';

        }
        else
        {
        	$rules['mobileno'] 		= 'required|numeric|digits:10';
	        $rules['DeviceToken'] 	= 'required';
        }


        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            try {
                if (PhoneNumber::make($input['mobileno'], 'IN')->isOfType('mobile')) {
                    $response = User::Applogin($input);
                } else {
                    $response['replyStatus'] = false;
                    $response['replyMessage'] = "invalid number mobile no";
                }
            } catch (NumberParseException $e) {
                $response['replyStatus'] = false;
                $response['replyMessage'] = "invalid number mobile no";
            }
        }

        return response(json_encode($response), 200)
        ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#

    #<=<=<=<=<=<=<=<=<=<=<API to refresh a signed-in user's push token<=<=<=<=<=<=<=<=<=<=<=<=#
    // login() writes DeviceToken once, at sign-in - a token can rotate later
    // (reinstall, OS-level refresh), and a user who stays signed in across
    // that never calls login() again. The client calls this on every app
    // launch instead, so the token users pushes actually go to stays current.
    public function updateDeviceToken(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id'    => 'required|integer',
            'DeviceToken' => 'required',
        );

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $updated = User::where('id', $input['user_id'])->update(['DeviceToken' => $input['DeviceToken']]);
            $response['replyStatus'] = (bool) $updated;
            $response['replyMessage'] = $updated ? 'Device token updated.' : 'User not found.';
        }

        return response(json_encode($response), 200)
        ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#

    #<=<=<=<=<=<=<=<=<=<=<=<=API For OTP Verification<=<=<=<=<=<=<=<=<=<=<<=<=<=<=<<=<=<=<=<=<=#
    public function user_verification(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'mobileno' => 'required|numeric|digits:10',
            'verification_code' => 'required|numeric',
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = User::AppVerification($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#

    #<=<=<=<=<=<=<=<=<=<=<=<=Scan QR Code<=<=<=<=<=<=<<=<<=<=<=<<=<<=<=<=<<=<=<=<=<<=<=<=<=<=<=#
    public function scanQR(Request $request)
    {
        $input = Input::all();
        $rules = array(
                'user_id' => 'required',
                'qrcode'=>'required'

            );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = Products::AppReward($input);
        }
        return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#

    #<=<=<=<=<=<=<=<=<=<=<=<=Reward History for Logged in User<=<=<=<=<=<=<<=<=<=<=<<=<=<=<=<=<=#
    public function rewardHistory(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id' => 'required',

        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = CustomerPoints::rewardHistory($input);
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#


    #<=<=<=<=<=<=<=<=<=<=<=<=Redeem History for Logged in User<=<=<=<=<=<=<<=<=<=<=<<=<=<=<=<=<=#
    public function userRedeemHistory(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id' => 'required',

        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = CustomerPoints::redeemHistory($input);
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#


    #<=<=<=<=<=<=<=<=Reward Product Categories for the App<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function rewardProductCategories(Request $request)
    {
        $input = Input::all();
        $response = RewardProduct::rewardProductCategories($input);
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#


    #<=<=<=<=<=<=<=<=Reward Product Catalog for the App<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function rewardProducts(Request $request)
    {
        $input = Input::all();
        $response = RewardProduct::rewardProducts($input);
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#


    #<=<=<=<=<=<=<=<=Claim a Reward Product against Points<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function claimRewardProduct(Request $request)
    {
        $input = Input::all();
        # A basket ("items") replaces the single product pair, so exactly one of
        # the two shapes has to be present - required_without keeps both valid.
        $rules = array(
            'user_id' => 'required|numeric|not_in:0',
            'reward_product_id' => 'required_without:items|nullable|numeric|not_in:0',
            'quantity' => 'sometimes|nullable|integer|min:1',
            'items' => 'required_without:reward_product_id',
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = RewardClaim::claimProduct($input);
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#


    #<=<=<=<=<=<=<=<=Product Claim History of the User<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function rewardClaimHistory(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id' => 'required',
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = RewardClaim::claimHistory($input);
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#



    public function infobox(Request $request)
    {
        $input = Input::all();
        $response=array();
        $response = Infobox::AppList($input);





        return response(json_encode($response), 200)
        ->header('Content-Type', 'application/json');
    }

    public function Home(Request $request)
    {


       $userdata = User::select('users.id', 'users.full_name', 'users.state', 'users.mobileno', 'users.email', 'users.city', 'users.profile_status', 'users.gender', 'users.profile_photo', 'users.dob', 'users.address', 'users.pincode', 'roles.slug as type', 'users.status', 'users.dob', 'users.account_type','users.dealer_id','dealers.dealer_name')
       ->where('users.id', $request->user_id)
       ->join('role_users', 'role_users.user_id', '=', 'users.id')
       ->join('roles', 'roles.id', '=', 'role_users.role_id')
        ->leftjoin('dealers', 'users.dealer_id', '=', 'dealers.user_id')
       ->first();


       if(isset($userdata) && !empty($userdata))
       {
            $data = Banner::select('id', 'title', 'description', 'image')->orderBy('id', 'desc')->get();

            $pointSummary=@CustomerPoints::getUserBalance($request->user_id);
            $point=$pointSummary['balance'];


	        if($userdata->full_name == '')
		    $userdata->full_name = '';

            $response['userdata'] = $userdata;
            $response['data'] = $data;

            if ($point) {
                $response['point'] = $point;
            } else {
                $response['point'] = 0;
            }

            $response['replyStatus'] = true;
            $response['replyMessage'] = '';
        }
        else
        {
            $response['replyStatus'] = false;
            $response['replyMessage'] = '';
        }







        return response(json_encode($response), 200)
        ->header('Content-Type', 'application/json');
    }

    public function notification(Request $request)
    {
        $input = Input::all();
        $rules = array(
                'type' => 'required',

            );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $data = Notification::select('id', 'title', 'description', 'image', 'created_at')
                ->whereIn('user_id', [0,$request->user_id])
                ->orderBy('id', 'desc')->get();
            $response['data'] = $data;
            $response['replyStatus'] = true;
            $response['replyMessage'] = '';
        }
        return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
    }

    #<=<=<=<=<=<=<=<=Delete one notification<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function notificationDelete(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id' => 'required|numeric|not_in:0',
            'id'      => 'required|numeric',
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            # Scoped to the requester's own user_id, so a broadcast row
            # (user_id 0) or another user's notification can never be
            # deleted through this endpoint.
            $deleted = Notification::where('id', $request->id)
                ->where('user_id', $request->user_id)
                ->delete();

            $response['replyStatus']  = (bool) $deleted;
            $response['replyMessage'] = $deleted ? 'Notification deleted.' : 'Notification not found.';
        }
        return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#

    #<=<=<=<=<=<=<=<=Clear every notification for one user<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#
    public function notificationClear(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id' => 'required|numeric|not_in:0',
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            Notification::where('user_id', $request->user_id)->delete();

            $response['replyStatus']  = true;
            $response['replyMessage'] = 'Notifications cleared.';
        }
        return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
    }
    #=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>#
    public function StateList(Request $request)
    {
        $data = States::select('name', 'id')->where('status', 'Active')->orderBy('name', 'asc')->get();
        $response['data'] = $data;

        $response['replyStatus'] = true;
        $response['replyMessage'] = '';


        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }

    public function pincode(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'pincode' => 'required',

        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $data = Pincode::select('district', 'state')->where('pincode', $request->pincode)->first();
            $response['data'] = $data;
            $response['replyStatus'] = true;
            $response['replyMessage'] = '';
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    public function DistrictList(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'state_id' => 'required',

        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $data = City::select('id', 'name')->where('state_id', $request->state_id)->where('status', 'Active')->orderBy('name', 'asc')->get();
            $response['data'] = $data;
            $response['replyStatus'] = true;
            $response['replyMessage'] = '';
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    public function uploadpic(Request $request)
    {
        $input=Input::all();
        $status="";

        $rules = array(
                    // 'name'=>'required',
                    // 'phoneno'=>'required|numeric|digits:10',
                    'user_id'=>'required'

        );

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            try {
                $file=$request->image;

                // print_r($file->data);die();
                $file=$file['data'];
                $file_data=base64_decode($file);
                $img = substr($file_data, strpos($file_data, ",")+1);

                // $var= json_encode($data);
                $extension = '.jpeg';

                $folderName = '/user/';
                @mkdir(storage_path('app/uploads/').$folderName, 0777, true);
                @chmod(storage_path('app/uploads/').$folderName, 0777);
                $image = Str::random(10) . $extension;
                $test=file_put_contents(storage_path('app/uploads/').$folderName.'/'.$image, $file_data, FILE_APPEND);

                $data = User::where('id', $input['user_id'])
            ->update(['profile_photo'=>$image]);


                $userdata = User::select('users.id', 'users.full_name', 'users.state', 'users.mobileno', 'users.email', 'users.city', 'users.profile_status', 'users.gender', 'users.profile_photo', 'users.dob', 'users.address', 'users.pincode', 'roles.slug as type','users.account_type','users.dealer_id')

        ->where('users.id', $input['user_id'])
        ->join('role_users', 'role_users.user_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'role_users.role_id')
        ->first();

                $response['data'] = $userdata;
                $response['success'] = true;
                $response['message'] = '';
            } catch (\Illuminate\Database\QueryException $e) {
                $status = 'error';
                $msg	= "Error IN Query : ".$e->getMessage();
            } catch (PDOException $e) {
                $status = 'error';
                $msg	= "Error IN Query : ".$e->getMessage();
            } catch (\Exception $e) {
                $status = 'error';
                $msg	= $e->getMessage();
            }
            if ($status=='error') {
                $response['success'] = false;
                $response['message'] = $msg;
            }
        }


        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }
    public function Wallet(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'user_id' => 'required',

        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = CustomerPoints::AppList($input);
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }

    #<=<=<=<=<=<=<=<=RETIRED - cash redemption was replaced by product claims<=<=<=<=<=<=<=<=#
    # Kept as a route so installed app builds get a readable message instead of a 404.
    public function redeemRequest(Request $request)
    {
        $response = array(
            'replyStatus'  => false,
            'replyMessage' => 'Cash redemption is no longer available. Please update the app and claim a reward product with your points.',
        );

        return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
    }

    public function StaticPage(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'slug' => 'required',

        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $data = StaticPage::select('page_description')
            ->where('slug', $request->slug)
            ->first()->page_description;
            $response['data'] = $data;

            $response['replyStatus'] = true;
            $response['replyMessage'] = '';
        }
        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }






    #<=<=<=<=<=<=<=<=RETIRED - cash redemption was replaced by product claims<=<=<=<=<=<=<=<=#
    # Kept as a route so installed app builds get a readable message instead of a 404.
    # Points are now spent through v1/claim-reward-product.
    public function userRedeemPoints(Request $request)
    {
        $response = array(
            'replyStatus'  => false,
            'replyMessage' => 'Cash redemption is no longer available. Please update the app and claim a reward product with your points.',
        );

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }



    public function redeempoints(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'mobileno' => 'required|numeric|digits:10',
            'user_id' => 'required|numeric|not_in:0',
            'qty' => 'required|numeric|not_in:0',
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = CustomerPoints::redeempoints($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }

    public function verifyredeempoints(Request $request)
    {
        $input = Input::all();
        $rules = array(
            'mobileno' => 'required|numeric|digits:10',
            'user_id' => 'required|numeric|not_in:0',
            'qty' => 'required|numeric|not_in:0',
            'otp'=>'required|numeric'
        );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = CustomerPoints::verifyredeempoints($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }

    public function state()
    {
        $response = array();

        //$response['data'] =States::select('_id','name')->where('status',"Active")->orderBy('name')->get();
        //die("OK");
        $response['replyStatus'] = true;
        $response['replyMessage'] = '';
        return response(json_encode($response), 200)
        ->header('Content-Type', 'application/json');
    }

    public function editProfile(Request $request)
    {
        $input = Input::all();


        $rules['mobileno'] 			= 'required|numeric|digits:10';
		$rules['full_name'] 		= 'required';
		$rules['address'] 			= 'required';
		$rules['pincode'] 			= 'required';
		$rules['city'] 				= 'required';
		$rules['state'] 			= 'required';
		$rules['dealer_mobile'] 	= 'required|numeric|digits:10';
		
        if($input['email']!='')
        {
            $rules['email'] 	= 'email|unique:users';
        }
		

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            $response = $this->validationError($validator);
        } 
        else 
        {
	        
	        $dealerId = Dealer::checkDealerByMobileNumber($input['dealer_mobile']);
	        if($dealerId!= NULL)	//if dealer_mobile number is a valid mobile number then it will execuite
			{
				$input['dealer_id'] = $dealerId;
				$response = User::AppeditProfile($input);
			}
			else
			{
				$response['replyStatus'] = false;
				$response['replyMessage'] = "Dealer mobile number is not registered with us";
			}
            
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }

    public function skipEditProfile(Request $request)
    {
        $input = Input::all();


        $rules['mobileno'] 	= 'required|numeric|digits:10';

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = User::AppSkipEditProfile($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }



    public function editBankDetails(Request $request)
    {
        $input = Input::all();

        if(isset($input['payment_type']) && $input['payment_type'] == 'Bank Details')
        {
	        $rules = array(
	            'user_id' => 'required|numeric',
	            'bank_name' => 'required',
	            'branch_name' => 'required',
	            'ifsc_code' => 'required|min:11|max:11',
	            'account_number' => 'required|numeric',
	        );
        }
        else if(isset($input['payment_type']) && $input['payment_type'] == 'UPI')
        {
	       $rules = array(
	       	    'user_id' => 'required|numeric',
	            'upi_id'=>'required',
	        );

        }
        else if(isset($input['payment_type']) && $input['payment_type'] == 'GPAY')
        {
           $rules = array(
                   'user_id' => 'required|numeric',
                   'gpay_number'=>'required',
            );

        }
        else
        {

        }


        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = User::AppEditBankDetails($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }


    public function skipEditBankDetails(Request $request)
    {
        $input = Input::all();

        $rules = array(
	       	    'user_id' => 'required|numeric',
	        );


        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = User::AppSkipEditBankDetails($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }


    public function skipUploadDocuments(Request $request)
    {
        $input = Input::all();

	    $rules = array(
	            'user_id' => 'required|numeric',
	   );

        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        }
        else {

        try {


            #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
            $isUserDeleted = User::checkUserDeleted('userid',$input['user_id']);
            if($isUserDeleted == false)
            {
                $response['replyStatus'] = false;
                $response['replyMessage'] = 'User Not found or removed';
                return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
            }
            #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#

				User::where('id', $input['user_id']) ->update(['step_completed' => 2]);

				$response['data']  = User::select('id','full_name','state','mobileno','email','city','profile_status','gender','profile_photo','dob','address','address_line2','pincode','user_type','account_type','profile_status','step_completed','dealer_id')->where('id', $input['user_id'])->first();

				$status = 'success';
	            $msg = 'Document Upload Skipped Sucessfully';


			} catch (\Illuminate\Database\QueryException $e) {
                $status = 'error';
                $msg	= "Error IN Query : ".$e->getMessage();
            } catch (PDOException $e) {
                $status = 'error';
                $msg	= "Error IN Query : ".$e->getMessage();
            } catch (\Exception $e) {
                $status = 'error';
                $msg	= $e->getMessage();
            }


            if ($status=='error') {
                $response['replyStatus'] = false;
                $response['message'] = $msg;
            }


            $response['replyStatus'] = $status;
			$response['replyMessage'] = $msg;

        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }

    public function uploadDocuments(Request $request)
    {
        $input = Input::all();
        $rules['user_id'] = 'required|numeric';
        $rules['document_type'] = 'required';
        $rules['front_image'] = 'required';

        if($input['document_type'] != 'Driving Licence'){
        $rules['back_image'] = 'required';
        }


        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        }
        else {

        try {


            #=>=>=>=>=> Check if user deleted =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=#
            $isUserDeleted = User::checkUserDeleted('userid',$input['user_id']);
            if($isUserDeleted == false)
            {
                $response['replyStatus'] = false;
                $response['replyMessage'] = 'User Not found or removed';
                return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
            }
            #<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=#

                $document_image_2 = NULL;
				//========Upload Front Images========================#
        		$file=$request->front_image;
                // print_r($file->data);die();
                $file=$file['data'];
                $file_data=base64_decode($file);
                $img = substr($file_data, strpos($file_data, ",")+1);

                // $var= json_encode($data);
                $extension = '.jpeg';

                $folderName = '/documents/';
                @mkdir(storage_path('app/uploads/').$folderName, 0777, true);
                @chmod(storage_path('app/uploads/').$folderName, 0777);
                $document_image_1 = Str::random(10) . $extension;
                $test=file_put_contents(storage_path('app/uploads/').$folderName.'/'.$document_image_1, $file_data, FILE_APPEND);


    			//========Upload Back Images========================#
                if(isset($request->back_image))
                {
        		    $file=$request->back_image;
                    // print_r($file->data);die();
                    $file=$file['data'];
                    $file_data=base64_decode($file);
                    $img = substr($file_data, strpos($file_data, ",")+1);

                    // $var= json_encode($data);
                    $extension = '.jpeg';

                    $folderName = '/documents/';
                    @mkdir(storage_path('app/uploads/').$folderName, 0777, true);
                    @chmod(storage_path('app/uploads/').$folderName, 0777);
                    $document_image_2 = Str::random(10) . $extension;
                    $test=file_put_contents(storage_path('app/uploads/').$folderName.'/'.$document_image_2, $file_data, FILE_APPEND);
                }

				//==========insert into tbl_user_documents=========#
				$userDoc = new Document();
				$userDoc->user_id = $input['user_id'];
				$userDoc->document_image_1 = $document_image_1;
				$userDoc->document_image_2 = $document_image_2;
				$userDoc->document_type = $input['document_type'];
				$userDoc->status = 'Active';
				$userDoc->save();

				User::where('id', $input['user_id']) ->update(['step_completed' => 2]);

				$response['data']  = User::select('id','full_name','state','mobileno','email','city','profile_status','gender','profile_photo','dob','address','pincode','user_type','account_type','dealer_id','profile_status','step_completed')->where('id', $input['user_id'])->first();

				$status = 'success';
	            $msg = 'Document Sucessfully Uploaded';


			} catch (\Illuminate\Database\QueryException $e) {
                $status = 'error';
                $msg	= "Error IN Query : ".$e->getMessage();
            } catch (PDOException $e) {
                $status = 'error';
                $msg	= "Error IN Query : ".$e->getMessage();
            } catch (\Exception $e) {
                $status = 'error';
                $msg	= $e->getMessage();
            }


            if ($status=='error') {
                $response['replyStatus'] = false;
                $response['message'] = $msg;
            }


            $response['replyStatus'] = $status;
			$response['replyMessage'] = $msg;

        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }



    public function userProfileStatus(Request $request)
    {
        $input = Input::all();

	    $rules = array(
	            'user_id' => 'required|numeric',
	   );


        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = User::AppUserProfileStatus($input);
        }

        return response(json_encode($response), 200)
            ->header('Content-Type', 'application/json');
    }


    /*
        public function home(Request $request) {
            $input = Input::all();
            $rules = array(
                'cat_id' => 'required',

            );
            $validator = \Validator::make($input, $rules);
            if ($validator->fails()) {
                $response = $this->validationError($validator);
            } else {

            //	$response = Exam::AppList($input);
            }

            return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
        }


    */


    public function validationError($validator)
    {
        //pass validator errors as errors object for ajax response
        $errors = $validator->errors()->getMessages();
        $errorStr = '';
        foreach ($errors as $error) {
            foreach ($error as $erVal) {
                $errorStr .= $erVal;
                $errorStr .= "\n";
            }
        }
        $response['replyStatus'] = false;
        $response['replyMessage'] = $errorStr;
        return $response;
    }




    //Functions not to use=========#
    public function dealermobileno(Request $request)
    {
        $response=array();
        $input = Input::all();
        $rules = array(
                'mobileno' => 'required',
            );
        $validator = \Validator::make($input, $rules);
        if ($validator->fails()) {
            $response = $this->validationError($validator);
        } else {
            $response = User::AppDealerCheck($input);
        }
        return response(json_encode($response), 200)
                ->header('Content-Type', 'application/json');
    }
}
