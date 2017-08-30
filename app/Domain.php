<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domain extends Model
{
    use SoftDeletes;

    #region PROPERTIES
    protected $table = 'domains';
    protected $dates = ['deleted_at'];
    protected $fillable = [
            'lead_id',
            'value'
        ];
    #endregion

    #region MAIN METHODS
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    #endregion

    #region SERVICE METHODS
    #endregion
}
