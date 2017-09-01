<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    #region PROPERTIES
    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'lead_id',
        'comment'
    ];
    #endregion

    #region MAIN METHODS
    #endregion

    #region RELATION METHODS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    #endregion

    #region SERVICE METHODS
    #endregion
}
