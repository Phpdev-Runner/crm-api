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
    public static function getCommunicationChannels()
    {
        return self::all();
    }

    public static function getChannelID(string $name)
    {
        return self::where('name','=',$name)->first()->id;
    }
    #endregion

    #region RELATION METHODS
    public function communicationValues()
    {
        return $this->hasMany(CommunicationValue::class);
    }
    #endregion

    #region SERVICE METHODS
    #endregion
}
