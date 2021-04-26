<?php 

namespace Webdevjohn\SelectBoxes;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelFactory {

	public function make(string $model) :Model|Exception
	{
		$model = Str::title($model);

		$model = new $model;
	
		if ($this->isEloquentModel($model)) return $model;
	
		throw new Exception("Model creation error: " . get_class($model) . " is not an instance of \Illuminate\Database\Eloquent\Model");
	}


  	protected function isEloquentModel($model)
  	{
  		if ($model instanceof \Illuminate\Database\Eloquent\Model) {
  			return true;
  		}
  		return false;
  	}
}
