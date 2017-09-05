<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
     */
    public function rules()
    {
        return [
            'name'=>'required|min:3',
            'email'=>[
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('id'),'id')
            ],
            'role_id'=>'required|exists:roles,id'
        ];
    }
}
