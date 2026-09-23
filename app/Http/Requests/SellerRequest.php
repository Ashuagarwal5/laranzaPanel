<?php

namespace App\Http\Requests;

class SellerRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       
                return [
                    'first_name' => 'required|min:3',
                    'last_name' => 'required|min:3',
                    'email' => 'required|email',
                    'mobileno'=>'required',
                   
                    
                  
                    'company_name' => 'required|min:3',
                    'category' => 'required',
                    'city' => 'required',
                    'state'=>'required',
                    'company_telephone' => 'required',
                    'postcode'=>'required',
                    'address'=>'required',
                    'address'=>'required',
                    'designation'=>'required',
                    'store_name'=>'required|min:6|max:15|regex:/^[a-zA-Z0-9\-]+$/'

                   
                    
                ];
           
     }

}

