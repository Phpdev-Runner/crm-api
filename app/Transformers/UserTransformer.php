<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 15.08.2017
 * Time: 12:18
 */

namespace App\Transformers;


class UserTransformer extends Transformer {
	
	public function transformMany($users)
	{
//		dd($user);
		return [
			'id'=>$users['id'],
			'name'=>$users['name'],
			'email'=>$users['email']
		];
	}
	
	public function transformOne($user)
	{
//		dd($user);
		return [
			'id'=>$user['id'],
			'name'=>$user['name'],
			'email'=>$user['email'],
			'role_is'=>$user['role_id'],
			'role'=>$user['role']['name']
			
		];
	}
	
}