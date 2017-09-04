<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 04.09.2017
 * Time: 15:45
 */

namespace App\Transformers;


class CreateComRecordTransformer extends TransformerForEmptyForm
{
    public function transformFormData($data)
    {
//		dd($data);
        $returnData = [];
        foreach($data AS $key=>$value)
        {
            $returnData[] = [
                'id'=>$value['id'],
                'name'=>$value['name']
            ];
        }
        return $returnData;
    }
}