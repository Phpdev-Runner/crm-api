<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 15.08.2017
 * Time: 12:18
 */

namespace App\Transformers;


class UserTransformer extends Transformer {
	
	public function transform($user)
	{
		return [
			'title'=>$user['name'],
			'email'=>$user['email']
		];
	}
	
}