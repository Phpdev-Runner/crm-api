<?php

namespace App\Transformers;

abstract class Transformer
{
	
	// transform collection of collections
	public function transformManyCollections(array $items)
	{
		return array_map([$this,'transformMany'], $items);
	}
	
	public abstract function transformMany($items);
	
	// transform one collection
	public function transformOneCollection(array $item)
	{
		return $this->transformOne($item);
	}
	
	public abstract function transformOne($item);
}