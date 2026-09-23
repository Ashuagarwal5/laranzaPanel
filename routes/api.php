<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

#Route::middleware('auth:api')->get('/user', function (Request $request) {
    #return $request->user();
#});
Route::group(array('prefix' => 'v1','middleware' => 'SentinelApi'), function () {
    
    Route::get('settings', array('as' => 'v1.settings', 'uses' => 'ApiController@settings'));
    
    Route::post('login', array('as' => 'v1.login', 'uses' => 'ApiController@login'));

    Route::post('update-device-token', array('as' => 'v1.update.device.token', 'uses' => 'ApiController@updateDeviceToken'));
    
    Route::post('user_verification', array('as' => 'v1.user_verification', 'uses' => 'ApiController@user_verification'));
  
    Route::post('editProfile', array('as' => 'v1.editProfile', 'uses' => 'ApiController@editProfile'));

    Route::post('skipEditProfile', array('as' => 'v1.skipeditProfile', 'uses' => 'ApiController@skipEditProfile'));
            
    Route::post('scanqr', array('as' => 'v1.scanqr', 'uses' => 'ApiController@scanQR'));  
    
    Route::post('reward-history', array('as' => 'v1.reward.history', 'uses' => 'ApiController@rewardHistory'));  
   
    Route::post('redeem-points', array('as' => 'v1.redeem.points', 'uses' => 'ApiController@userRedeemPoints'));   
 
    Route::post('redeem-history', array('as' => 'v1.redeem.history', 'uses' => 'ApiController@userRedeemHistory'));

    Route::post('reward-product-categories', array('as' => 'v1.reward.product.categories', 'uses' => 'ApiController@rewardProductCategories'));

    Route::post('reward-products', array('as' => 'v1.reward.products', 'uses' => 'ApiController@rewardProducts'));

    Route::post('claim-reward-product', array('as' => 'v1.claim.reward.product', 'uses' => 'ApiController@claimRewardProduct'));

    Route::post('claim-history', array('as' => 'v1.claim.history', 'uses' => 'ApiController@rewardClaimHistory'));

    Route::get('state', array('as' => 'v1.state', 'uses' => 'ApiController@StateList'));
  
    Route::post('district', array('as' => 'v1.district', 'uses' => 'ApiController@DistrictList'));

    Route::post('pincode', array('as' => 'v1.pincode', 'uses' => 'ApiController@pincode'));

    Route::post('home', array('as' => 'v1.home', 'uses' => 'ApiController@Home'));
                  
    Route::post('notification', array('as' => 'v1.notification', 'uses' => 'ApiController@notification'));
    Route::post('notification/delete', array('as' => 'v1.notification.delete', 'uses' => 'ApiController@notificationDelete'));
    Route::post('notification/clear', array('as' => 'v1.notification.clear', 'uses' => 'ApiController@notificationClear'));
   
    Route::post('skip_edit_bank_details', array('as' => 'v1.skipeditbankdetails', 'uses' => 'ApiController@skipEditBankDetails'));
       
    Route::post('edit_bank_details', array('as' => 'v1.editbankdetails', 'uses' => 'ApiController@editBankDetails'));
    
    Route::post('upload_documents', array('as' => 'v1.uploaddocuments', 'uses' => 'ApiController@uploadDocuments'));   
    
    Route::post('skip_upload_documents', array('as' => 'v1.skipuploaddocuments', 'uses' => 'ApiController@skipUploadDocuments'));   
    
    Route::post('profile_status', array('as' => 'v1.profile.status', 'uses' => 'ApiController@userProfileStatus'));   
             
    #Route::post('redeempoints', array('as' => 'v1.redeempoints', 'uses' => 'ApiController@redeempoints'));  
    #Route::post('verifyredeempoints', array('as' => 'v1.verifyredeempoints', 'uses' => 'ApiController@verifyredeempoints'));
        
    

    



    
    
    Route::post('wallet', array('as' => 'v1.wallet', 'uses' => 'ApiController@Wallet'));




    Route::post('infobox', array('as' => 'v1.infobox', 'uses' => 'ApiController@infobox'));
    Route::post('uploadpic', array('as' => 'v1.uploadpic', 'uses' => 'ApiController@uploadpic'));

    Route::post('dealermobileno', array('as' => 'v1.dealermobileno', 'uses' => 'ApiController@dealermobileno'));
    Route::post('redeemRequest', array('as' => 'v1.redeemRequest', 'uses' => 'ApiController@redeemRequest'));
});

// Celitix / Meta delivery receipts. Public by design - authenticated by the
// verify token on subscription, then matched to a log row by wamid.
Route::get('whatsapp/webhook', array('as' => 'whatsapp.webhook.verify', 'uses' => 'WhatsappWebhookController@verify'));
Route::post('whatsapp/webhook', array('as' => 'whatsapp.webhook', 'uses' => 'WhatsappWebhookController@handle'));
