<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 01.09.2017
 * Time: 10:37
 */

namespace App\Http\Requests;

use App\Http\Controllers\ApiController;

trait ResponseTrait
{
    public function response(array $errors) {

        $message = [];
        foreach ($errors AS $field=>$data){
            foreach ($data AS $key=>$text){
                $message[] = $text;
            }
        }
        $api = new ApiController();

        return $api->respondValidationFailed($message);
    }
}