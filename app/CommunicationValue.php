<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunicationValue extends Model
{
    #region PROPERTIES
    protected $table = 'communication_values';
    protected $fillable = [
        'lead_id',
        'channel_id',
        'value'
    ];
    #endregion

    #region MAIN METHODS

    public function communicationChannel()
    {
        return $this->belongsTo(CommunicationChannel::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    #endregion

    #region SERVICE METHODS
    #endregion
}
