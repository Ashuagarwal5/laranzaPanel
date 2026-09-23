<?php

namespace App\Http\Requests;


class AppUserRequest extends Request
 {
 
 
 public function authorize()
    {
        return true;
    }
    
  public function rules()
    {
        return [
           
             
          ];
    }   
    
    
    
}
