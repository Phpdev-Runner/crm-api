<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLeadPost extends FormRequest
{
    use ResponseTrait;
	
	/**
     * Determine if the user is authorized to make this request.
     * @todo finish authorization
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
     * @todo finish validation
     */
    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:lead_categories,id',
            'application_id' => 'required|integer|exists:application_types,id',
            'assignee_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|min:6',
            'responsive' => 'required|integer|digits_between:0,1',
            'domains' => 'required',
        ];
    }
    
    public function withValidator($validator)
    {
    
    }
}
