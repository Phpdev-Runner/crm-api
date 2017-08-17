<?php

namespace App\Transformers;

abstract class Transformer {
	
	public function transformManyCollections(array $items)
	{
		return array_map([$this,'transformMany'], $items);
	}
	
	public abstract function transformMany($item);
	
	public function transformOneCollection(array $entity)
	{
		return $this->transformOne($entity);
	}
	
	public abstract function transformOne($entity);
}