<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 15.08.2017
 * Time: 12:18
 */

namespace App\Transformers;


class UserTransformer extends Transformer {
	
	public function transformMany($user)
	{
		return [
			'id'=>$user['id'],
			'name'=>$user['name'],
			'email'=>$user['email'],
			'role_id'=>$user['role_id'],
			'role_name'=>$user['role']['name']
		];
	}
	
	public function transformOne($user)
	{
		return [
			'id'=>$user['id'],
			'name'=>$user['name'],
			'email'=>$user['email'],
			'role_id'=>$user['role_id'],
			'role_name'=>$user['role']['name']
		];
	}
	
}