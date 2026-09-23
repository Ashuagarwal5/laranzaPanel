<?php

namespace App\Helpers;
use Validator;
class AjaxFormValidator{

    public static function make(array $data, array $rules, array $messages = [], array $customAttributes = []){
        $validator=Validator::make($data, $rules, $messages,$customAttributes);
        // dd('working');
        $errorMsg = "Oops ! Some Error Occured. Please Try Again.";
        if ($validator->fails()) {
            return ['status'=>'error','errorArray' => $validator->errors(), 'error_msg' => $errorMsg,'slideToTop'=>true];
        } else{
            return ['status'=>'success'];
        }
    }

}