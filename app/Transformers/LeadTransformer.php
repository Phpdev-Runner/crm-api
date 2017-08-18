<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 17.08.2017
 * Time: 15:34
 */

namespace App\Transformers;


class LeadTransformer extends Transformer
{
	public function transformMany($lead)
	{
//		dd($lead);
		return [
			'id'=>$lead['id'],
			'name'=>$lead['name'],
			'responsive'=>$lead['responsive'],
			'category_id'=>$lead['category_id'],
			'category_name'=>$lead['lead_category']['name'],
			'application_type_id'=>$lead['application_type_id'],
			'application_type_name'=>$lead['application_type']['name']
		];
	}
	
	public function transformOne($lead)
	{
		dd($lead);
	}
}