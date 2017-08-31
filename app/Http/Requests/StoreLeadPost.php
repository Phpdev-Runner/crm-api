<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLeadPost extends FormRequest
{
	
	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|integer|min:1',
            'application_id' => 'required|integer|min:1',
            'assignee_id' => 'required|integer|min:1',
            'name' => 'required|string',
            'responsive' => 'required|integer|digits_between:0,1',
//            'domains'
        ];
    }
	
	public function response(array $errors) {
		
		$message = [];
		foreach ($errors AS $field=>$data){
			foreach ($data AS $key=>$text){
				$message[] = $text;
			}
		}
    	$api = new ApiController();
		
		return $api->respondValidationFailed($message);
	}
}
