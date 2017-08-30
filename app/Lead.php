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
		$allLeads = self::with('leadCategory')->
			with('applicationType')->
			with('creator')->
			with('assignee')->get();
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
	
	public function creator()
	{
		return $this->belongsTo(User::class, 'creator_id','id');
	}
	
	public function assignee()
	{
		return $this->belongsTo(User::class,'assignee_id','id');
	}

	public function domains()
    {
        return $this->hasMany(Domain::class);
    }
	#endregion
	
	#region SERVICE METHODS
	
	#endregion
}
