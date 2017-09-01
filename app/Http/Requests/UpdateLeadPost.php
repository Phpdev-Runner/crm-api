<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLeadPost extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     * @todo add more sophisticated validation check
     */
    public function authorize()
    {
        if(Auth::check()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * Get the validation rules that apply to the request.
     * @todo complete validation rules for domains and contacts
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:6',
            'category_id'=>'required|integer|min:1',
            'application_id'=>'required|integer|min:1',
            'assignee_id'=>'required|integer|min:1',
            'responsive'=>'required|integer|digits_between:0,1',
            'domains'=>'',
            'contacts'=>''
        ];
    }
}
