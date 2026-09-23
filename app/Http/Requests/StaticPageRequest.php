<?php

namespace App\Http\Requests;


class StaticPageRequest extends Request
 {
 
 
 public function authorize()
    {
        return true;
    }
    
  public function rules()
    {
        return [
            'page_title' => 'required',
             'page_description' => 'required'
             
          ];
    }   
    
    
    
}
