<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
	use SoftDeletes;
	
    #region CLASS PROPERTIES
	protected $table = 'leads';
	protected $fillable = [
		'category_id',
		'application_type_id',
		'creator_id',
		'assignee_id',
		'name',
		'responsive'];
	protected $dates = ['deleted_at'];
	#endregion
	
	#region MAIN METHODS
	public static function viewAllLeads()
	{
		$allLeads = self::get();
		return $allLeads;
	}
	
	public function leadCategory()
	{
		return $this->belongsTo(LeadCategory::class,'category_id','id');
	}
	
	public function applicationType()
	{
		return $this->belongsTo(ApplicationType::class, 'application_type_id','id');
	}
	#endregion
	
	#region SERVICE METHODS
	
	#endregion
}
