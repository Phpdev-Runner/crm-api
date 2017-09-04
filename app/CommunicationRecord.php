<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunicationRecord extends Model
{
    #region CLASS PROPERTIES
    protected $table = 'communication_records';
    protected $fillable = [
        'channel_id',
        'lead_id',
        'user_id',
        'value'
    ];
    #endregion

    #region MAIN METHODS
    #endregion

    #region RELATION METHODS
    public function communicationChannel()
    {
        return $this->belongsTo(CommunicationChannel::class);
    }

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
