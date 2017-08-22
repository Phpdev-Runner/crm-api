<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationType extends Model
{
	#region CLASS PROPERTIES
	protected $table = 'application_types';
	#endregion
	
	#region MAIN METHODS
	public static function getApplicationTypes()
	{
		return self::all();
	}
	#endregion
	
	#region SERVICE METHODS
	
	#endregion
}
