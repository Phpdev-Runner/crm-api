<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    #region PROPERTIES
	protected $table = 'roles';
	private static $unauthorizedRoleName = 'unauthorized';
	#endregion
	
	#region MAIN METHODS
	
	public static function unauthorizedRoleId()
	{
		$unauthorizedRoleID = DB::table('roles')->
			where('name','=',self::$unauthorizedRoleName)->
			first();
		
		return $unauthorizedRoleID->id;
	}
	
	public function users()
	{
		return $this->hasMany(User::class);
	}
	#endregion
}
