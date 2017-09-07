<?php

namespace App\Http\Requests;

use App\PasswordResets;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class StorePasswordPost extends FormRequest
{

    #region CLASS PROPERTIES
    private $passwordReset;
    #endregion

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
            'token' => 'required',
            'email' => 'required|email|exists:password_resets,email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8'
        ];
    }

    public function withValidator($validator)
    {
        $token = urldecode(Input::get('token')).$_ENV['SALT'];
        $email = Input::get('email');
        $this->passwordReset = PasswordResets::where('email','=',$email)
            ->where('token','=',$token)
            ->get();

        $validator->after(function($validator){
            if(count($this->passwordReset) === 0){
                $validator->errors()->add('email', 'Submitted email must be equal to email you were invited to!');
            }
        });
    }
}
