<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 18.08.2017
 * Time: 16:29
 */

namespace App\Transformers;

class CreateUserTransformer extends TransformerForEmptyForm
{
	public function transformFormData($role)
	{
//		dd($role);
		return [
			'id'=>$role['id'],
			'name'=>$role['name']
		];
	}
}