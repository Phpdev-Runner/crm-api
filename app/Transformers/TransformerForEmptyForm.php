<?php

namespace App\Transformers;

abstract class TransformerForEmptyForm
{
	// transform data for empty form
	public function transformDataForEmptyForm(array $data)
	{
//		dd($data);
		return array_map([$this,'transformFormData'], $data);
	}
	
	public abstract function transformFormData($data);
}