<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 04.09.2017
 * Time: 15:44
 */

namespace App\Transformers;


use App\CommunicationChannel;
use App\User;

class ComRecordTransformer extends Transformer
{
    public function transformMany($comRecord)
    {
        dd("not configured yet");

        return [

        ];
    }

    public function transformOne($comRecord)
    {
//		dd($comRecord);

        return [
            'id' => $comRecord['id'],
            'lead_id'=> $comRecord['lead_id'],
            'channel_id' => $comRecord['channel_id'],
            'channel' => CommunicationChannel::getChannelNameById($comRecord['channel_id']),
            'user_id'=> $comRecord['user_id'],
            'user' => User::getUserNameById($comRecord['user_id']),
            'value' => $comRecord['value'],
            'updated_at' => $comRecord['updated_at']
        ];
    }
}