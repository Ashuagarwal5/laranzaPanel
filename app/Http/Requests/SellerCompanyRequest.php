<?php

namespace App\Http\Requests;

class SellerCompanyRequest extends Request
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
               //  'first_name' => 'required|min:3',
                  //  'last_name' => 'required|min:3',
                  //  'email' => 'required|email',
                  //  'mobileno'=>'required',
                    
					'company_name' => 'required|min:3',
					'address' => 'required',
					'city' => 'required',
					'state' => 'required',
					'company_telephone' => 'required',
					'postcode' => 'required',
                   // 'company_logo' => 'required',
                   
                
                    
                ];
           
     }

}


