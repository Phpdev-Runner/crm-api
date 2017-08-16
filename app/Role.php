<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    #region PROPERTIES
	protected $table = 'roles';
	#endregion
	
	#region MAIN METHODS
	
	public static function unauthorizedRoleId()
	{
		$unauthorizedRoleID = self::where(
				'name',
				'=',config('roles.unauthorized'))->
			first();
		
		return $unauthorizedRoleID->id;
	}
	
	public static function getAllRoles()
	{
		$roles = self::all();
		return $roles;
	}
	
	public function users()
	{
		return $this->hasMany(User::class);
	}
	#endregion
}
