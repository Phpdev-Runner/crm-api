<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadCategory extends Model
{
    #region CLASS PROPERTIES
	protected $table = 'lead_categories';
	#endregion
	
	#region MAIN METHODS
	public static function getLeadCategories()
	{
		return self::all();
	}
	#endregion
	
	#region SERVICE METHODS
	
	#endregion
}
