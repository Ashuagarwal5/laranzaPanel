<?php
namespace App\Http\Controllers\Admin;
use App\WebsiteSetting;
use App\CustomerRewards;
use Cache;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;
use View;
use DB;
use Datatables;


class WebsitesettingController extends CodespurController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct() {
	}
	public function siteSetting() {
		$PARENT_ID = 3;
		$data = WebsiteSetting::select('*')->find(1);
		return view('admin.website.site-setting', compact('PARENT_ID', 'data'));
	}
	public function storeSiteSetting(Request $request) {
		Cache::flush('siteSettingList');
		$input = $request->all();
		$rules['site_name'] = "required";
		$rules['contact_no'] = "required";
		$rules['contact_email'] = "required";
		//$rules['redeem_status'] = "required";
		$rules['app_version'] = "required";
		$rules['min_req_points'] = "required";
		$rules['signup_bonus'] = "required";
		$rules['refund_points_redeem_cancelled'] = "required";
		$rules['point_value'] = "required";
		
		// $rules['address']			= "required";
		$rules['copyright_text'] = "required";
		// $rules['conversion_rate']		= "required|numeric";
		$msg = "Please fill required fields.";
		$msgHead = "Error !";
		$msgType = "error";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
		} else {
			$records = WebsiteSetting::Select('id', 'logo')->where('id', 1)->first();
			if ($file = $request->file('logo')) {
				$fileName = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$folderName = '/logo';
				$safeName = Str::random(10) . '.' . $extension;
				Storage::disk('uploads')->putFileAs($folderName, $file, $safeName);
				$input['logo'] = $safeName;
			}
			if (empty($input['logo'])) {
				$input['logo'] = $records->logo;
			}
			// $mode = implode(',',$request->mode);
			// $input['mode'] = $mode;
			$data = WebsiteSetting::updateOrCreate(['id' => 1], $input);
			$output['PARENT_ID'] = 3;
			$output['msg'] = "Site Settings Updated Successfully.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['slideToTop'] = true;
			return json_encode($output);
		}
	}
	public function point_settings() {
		$PARENT_ID = 68;
		$data = WebsiteSetting::select('point_set_less_10', 'point_set_greater_10')->find(1);
		return view('admin.website.point_settings', compact('PARENT_ID', 'data'));
	}
	public function point_settings_post(Request $request) {
		Cache::flush('siteSettingList');
		$input = $request->all();
		$rules['purchase_of_10_doller'] = "required";
		//$rules['purchase_of_10_doller_more'] = "required";
		$msg = "Please fill required fields.";
		$msgHead = "Error !";
		$msgType = "error";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
		} else {
			$input_save['point_set_less_10'] = $request->get('purchase_of_10_doller');
			$input_save['point_set_greater_10'] = $request->get('purchase_of_10_doller_more');
			$data = WebsiteSetting::updateOrCreate(['id' => 1], $input_save);
			$output['PARENT_ID'] = 3;
			$output['msg'] = "Point Settings Settings Updated Successfully.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['slideToTop'] = true;
			return json_encode($output);
		}
	}
	public function emailSetting() {
		$PARENT_ID = 3;
		$data = WebsiteSetting::select('*')->find(1);
		return view('admin.website.email-setting', compact('PARENT_ID', 'data'));
	}
	public function storeEmailSetting(Request $request) {
		//echo "complete"; die;
		Cache::flush('siteSettingList');
		$input = $request->all();
		$rules['goes_from_email'] = "required|email";
		$rules['goes_from_name'] = "required";
		$rules['contact_email'] = "required|email";
		$rules['customer_support_email'] = "required|email";
		$msg = "Please fill required fields.";
		$msgHead = "Error !";
		$msgType = "error";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
		} else {
			$data = WebsiteSetting::updateOrCreate(['id' => 1], $input);
			$output['PARENT_ID'] = 3;
			$output['msg'] = "Email Settings Updated Successfully.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['slideToTop'] = true;
			return json_encode($output);
		}
	}
	public function paymentSetting() {
		$PARENT_ID = 3;
		$data = WebsiteSetting::select('api')->find(1);
		return view('admin.website.payment-setting', compact('PARENT_ID', 'data'));
	}
	public function storePaymentSetting(Request $request) {
		Cache::flush('siteSettingList');
		$input = $request->all();
		$rules['api'] = "required";
		$msg = "Please fill required fields.";
		$msgHead = "Error !";
		$msgType = "error";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
		} else {
			$data = WebsiteSetting::updateOrCreate(['id' => 1], $input);
			$output['PARENT_ID'] = 3;
			$output['msg'] = "Payment Settings Updated Successfully.";
			$output['msgHead'] = "Success !";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['slideToTop'] = true;
			return json_encode($output);
		}
	}
	public function social_setting(Request $request) {
		// $d = WebsiteSetting::get()->toArray();
		// echo "<pre>";
		// print_r($d);
		$PARENT_ID = 3;
		Cache::flush('siteSettingList');
		$data = WebsiteSetting::find(1);
		if ($_POST) {
			$input = $request->all();
			$rules['facebook_url'] = "required";
			$rules['twitter_url'] = "required";
			$rules['instagram_url'] = "required";
			// $rules['pinterest_url']				= "required";
			// $rules['google_plus']				= "required";
			$msg = "Please fill required fields.";
			$msgHead = "Error !";
			$msgType = "error";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
			} else {
				$data = WebsiteSetting::updateOrCreate(['id' => 1], $input);
				$output['PARENT_ID'] = 3;
				$output['msg'] = "Settings Updated Successfully.";
				$output['msgHead'] = "Success !";
				$output['msgType'] = "success";
				$output['status'] = 'success';
				$output['slideToTop'] = true;
				return json_encode($output);
			}
		}
		return view('admin.website.social-setting', compact('PARENT_ID', 'data'));
	}
	public function store_setting(Request $request) {
		// $d = WebsiteSetting::get()->toArray();
		// echo "<pre>";
		// print_r($d);
		$PARENT_ID = 3;
		Cache::flush('siteSettingList');
		$data = WebsiteSetting::find(1);
		if ($_POST) {
			$input = $request->all();
			$rules['cod'] = "required";
			// $rules['pinterest_url']				= "required";
			// $rules['google_plus']				= "required";
			$msg = "Please fill required fields.";
			$msgHead = "Error !";
			$msgType = "error";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
			} else {
				$data = WebsiteSetting::updateOrCreate(['id' => 1], $input);
				$output['PARENT_ID'] = 3;
				$output['msg'] = "Settings Updated Successfully.";
				$output['msgHead'] = "Success !";
				$output['msgType'] = "success";
				$output['status'] = 'success';
				$output['slideToTop'] = true;
				return json_encode($output);
			}
		}
		return view('admin.website.store-setting', compact('PARENT_ID', 'data'));
	}
	public function seo_setting(Request $request) {
		$PARENT_ID = 3;
		$data = WebsiteSetting::find(1);
		if ($_POST) {
			Cache::flush('siteSettingList');
			$input = $request->all();
			$rules['site_title'] = "required";
			$rules['meta_keywords'] = "required";
			$rules['meta_description'] = "required";
			$msg = "Please fill required fields.";
			$msgHead = "Error !";
			$msgType = "error";
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
			} else {
				$data = WebsiteSetting::updateOrCreate(['id' => 1], $input);
				$output['PARENT_ID'] = 3;
				$output['msg'] = "Settings Updated Successfully.";
				$output['msgHead'] = "Success !";
				$output['msgType'] = "success";
				$output['status'] = 'success';
				$output['slideToTop'] = true;
				return json_encode($output);
			}
		}
		return view('admin.website.seo-setting', compact('PARENT_ID', 'data'));
	}
	public function messageSetting(Request $request){
		$PARENT_ID = 3;
		$data = WebsiteSetting::first();
		return view('admin.website.message-setting', compact('PARENT_ID', 'data'));
	}
	public function storeMessageSetting(Request $request){
		Cache::flush('siteSettingList');
		$input = $request->all();
		$rules['whatsaap_api_key'] = "required";
		$rules['waba_number'] = "required";
		$rules['sms_entity_id'] = "required";
		$rules['fcm_api_key'] = "required";
		$rules['fcm_icon_url'] = "required";
		$rules['fcm_service_account_json'] = "nullable|json";
		$msg = "Please fill required fields.";
		$msgHead = "Error !";
		$msgType = "error";
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(['errorArray' => $validator->errors(), 'msg' => $msg, 'msgHead' => $msgHead, 'msgType' => $msgType, 'slideToTop' => 'yes']);
		} else {
			$records = WebsiteSetting::first();
			$records->whatsaap_api_key = $request->whatsaap_api_key;
			$records->waba_number = $request->waba_number;
			$records->sms_entity_id = trim($request->sms_entity_id);
			$records->sms_api_key = trim((string) $request->sms_api_key);
			$records->fcm_api_key = $request->fcm_api_key;
			$records->fcm_icon_url = $request->fcm_icon_url;
			$records->fcm_service_account_json = trim((string) $request->fcm_service_account_json) ?: null;
			$records->save();
			$output['PARENT_ID'] = 3;
			$output['msg'] = "Site Settings Updated Successfully.";
			$output['msgHead'] = "Success ! ";
			$output['msgType'] = "success";
			$output['status'] = 'success';
			$output['slideToTop'] = true;
			return json_encode($output);
		}
	}
	public function bonusPointSetting() {
		$PARENT_ID = 3;
		$data = 'ok';
		$users=CustomerRewards::select('id','qualification_points','bonus_ponits')->orderBy('id','DESC')->get();
		return view('admin.website.bonus_points_setting', compact('PARENT_ID', 'data','users'));
	}
	
	public function bonusPointSettingdata(Request $request) {


		$data = CustomerRewards::select('id','qualification_points','bonus_ponits','points_from','points_to',DB::raw("DATE_FORMAT(`tbl_customer_rewards`.created_at,'%d %M  %Y ') as add_date"))->orderBy('id','DESC')->get();
	
		return Datatables::of($data)->make(true);
	}
}