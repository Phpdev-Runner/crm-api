<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordResets extends Model
{
    #region CLASS PROPERTIES
    protected $table = 'password_resets';
    public $timestamps  = false;
    #endregion

    #region MAIN METHODS
    public static function setTokenToMakeNewPassword(User $user)
    {
        $email = $user->email;
        $token = base64_encode($user->name.$user->email).$_ENV['SALT'];

        $passwordReset = new self;
        $passwordReset->email = $email;
        $passwordReset->token = $token;
        $passwordReset->created_at = Carbon::now();
        $passwordReset->save();

        return $passwordReset->id;
    }

    public static function checkTokenPresence($token)
    {
        $token = urldecode($token).$_ENV['SALT'];

        $resetPassword = self::where('token','=',$token)->first();
        return $resetPassword;
    }

    public static function deletePasswordResetRow($email)
    {
        self::where('email','=',$email)->delete();
    }

    #endregion

    #region SERVICE METHODS
    #endregion


}
