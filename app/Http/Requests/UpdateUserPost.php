<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserPost extends FormRequest
{
    use ResponseTrait;

    /**
     * Determine if the user is admin - otherwise he cannot edit manager.
     */
    public function authorize()
    {
        if(Auth::check() && Auth::user()->role->name == config('constants.roles.admin')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @todo add email duplicates validation later (other users email check for duplicates)
     */
    public function rules()
    {
        return [
            'name'=>'required|min:3',
            'email'=>'required|email',
            'role_id'=>'required|min:1'
        ];
    }
}
