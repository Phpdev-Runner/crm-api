<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 18.08.2017
 * Time: 17:56
 */

namespace App\Transformers;


class CreateLeadTransformer extends TransformerForEmptyForm
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