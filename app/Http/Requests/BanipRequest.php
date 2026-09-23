<?php

namespace App\Http\Requests;


class BanipRequest extends Request
 {
 
 
 public function authorize()
    {
        return true;
    }
    
  public function rules()
    {
        return ['ban_ip' => 'required|unique:ban_ip,ban_ip',
           
          ];
    }   
    
    
    
}
