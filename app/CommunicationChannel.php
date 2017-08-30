<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunicationChannel extends Model
{
    #region PROPERTIES
    protected $table = 'communication_channels';
    protected $fillable = [
        'name'
    ];
    #endregion

    #region MAIN METHODS

    public function communicationValues()
    {
        return $this->hasMany(CommunicationValue::class);
    }
    #endregion

    #region SERVICE METHODS
    #endregion
}
