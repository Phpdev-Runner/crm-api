<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
	
	public function hasRole($role)
	{
	
	}
	
	public function role()
	{
		return $this->belongsTo(Role::class);
	}
	#endregion
}
