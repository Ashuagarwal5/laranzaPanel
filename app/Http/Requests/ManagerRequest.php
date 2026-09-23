<?php

namespace App\Http\Requests;


class ManagerRequest extends Request
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
