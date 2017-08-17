<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    #region PROPERTIES
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    #endregion
	
	#region MAIN METHODS

	public static function getUsersWithRoles(array $roles)
	{
		$rolesIDs = self::getRolesIDs($roles);
		$users = self::whereIn('role_id',$rolesIDs)->get();
		return $users;
	}
	
	public function authHasRole()
	{
		return Auth::user()->role->name;
	}
	
	public function role()
	{
		return $this->belongsTo(Role::class);
	}
	#endregion
	
	#region SERVICE METHODS
	private static function getRolesIDs(array $roles)
	{
		foreach ($roles AS $key=>$role){
			$roles[$key] = Role::where('name','=',$role)->first()->id;
		}
		return $roles;
	}
	#endregion
}
