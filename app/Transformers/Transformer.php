<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Transformer
{
	// transform collection of collections
	public function transformManyCollections(Collection $collections)
	{
		$items = $collections->toArray();
		return array_map([$this,'transformMany'], $items);
	}
	
	public abstract function transformMany($items);
	
	// transform one collection
	public function transformOneModel(Model $model)
	{
		$item = $model->toArray();
		return $this->transformOne($item);
	}
	
	public abstract function transformOne($item);
}