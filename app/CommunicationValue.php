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
    public static function checkEmailDuplicates(array $emails)
    {
        $emailChannelID = CommunicationChannel::getChannelID(config('constants.communication_channel.email'));

        $duplicatedEmails = self::where('channel_id','=',$emailChannelID)
            ->whereIn('value',$emails)->get();

        if(count($duplicatedEmails) > 0){
            $returnData = [];
            foreach ($duplicatedEmails->toArray() AS $key=>$data){
                $returnData[] = $data['value'];
            }
            $returnData = implode(', ', $returnData);
            return $returnData;
        }else{
            return false;
        }

    }
    #endregion

    #region RELATION METHODS
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
