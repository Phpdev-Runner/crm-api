<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 05.09.2017
 * Time: 12:19
 */

namespace App\Http\Requests;
use App\CommunicationValue;


trait EmailDuplicateTrait
{
    private function checkEmailDuplicates(array $contacts, $leadID = false)
    {
        $emailFieldName = config('constants.communication_channel.email');

        $emailsArray = [];
        foreach ($contacts AS $key=>$data){
            if(array_key_exists($emailFieldName,$data)){
                $emailsArray[] = $data[$emailFieldName];
            }
        }
        $duplicatedEmails = CommunicationValue::checkEmailDuplicates($emailsArray, $leadID);

        return $duplicatedEmails;
    }
}