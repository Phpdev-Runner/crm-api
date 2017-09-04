<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    #region PROPERTIES
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];
    
	protected $dates = ['deleted_at'];

    protected $hidden = [
        'password', 'remember_token',
    ];
    #endregion
	
	#region MAIN METHODS

	public static function getUsersWithRoles(array $roles)
	{
		$rolesIDs = self::getRolesIDs($roles);
		$users = self::with('role')->whereIn('role_id',$rolesIDs)->get();
		return $users;
	}
	
	public static function checkNewEmailDuplicate($newEmail, $userID)
	{
		if($userID != null){
			$userWithDuplicateEmail = self::where('email','=',$newEmail)->
				where('id','<>', $userID)->first();
		}else{
			$userWithDuplicateEmail = self::where('email','!=',$newEmail)->
				first();
		}

		if($userWithDuplicateEmail === null){
			return false;
		}else{
			return true;
		}
	}

    public static function getRolesIDs(array $roles)
    {
        foreach ($roles AS $key=>$role){
            $roles[$key] = Role::where('name','=',$role)->first()->id;
        }
        return $roles;
    }

    public static function getUserNameById(int $id)
    {
	    return self::find($id)->name;
    }

    public function authHasRole()
    {
        return Auth::user()->role->name;
    }

	#endregion

    #region RELATION METHODS
	public function role()
	{
		return $this->belongsTo(Role::class);
	}

	public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function communicationRecords()
    {
        return $this->hasMany(CommunicationRecord::class);
    }

	#endregion
	
	#region SERVICE METHODS
	#endregion
}
