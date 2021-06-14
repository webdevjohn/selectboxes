<?php 

namespace Webdevjohn\SelectBoxes;

use Illuminate\Support\Collection;
use Webdevjohn\SelectBoxes\ModelFactory;

class SelectBoxService {
	
	/**
	 * The select box list.
	 */
	protected mixed $list;
	
	/**
	 * The option text to display in the select box.
	 */
	protected string $optionText;

	/**
	 * The option value for the select box.
	 */
	protected string $optionValue;

	public function __construct(
		protected ModelFactory $modelFactory
	){}


	/**
	 * Create a new list from a given model (with fully qualified namespace).    
	 *
	 * @param string $model
	 * 
	 * @return SelectBoxService
	 */
	public function createFrom(string $model): SelectBoxService
	{
		$this->list = $this->modelFactory->make($model);

		return $this;
	}	


	/**
	 * 
	 * @param string $optionText 
	 * @param string $optionValue 
	 * 
	 * @return SelectBoxService
	 */
	public function display(string $optionText, string $optionValue = 'id'): SelectBoxService
	{
		$this->optionText = $optionText;
		$this->optionValue = $optionValue;

		return $this;
	}


	/**	 
	 *
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * 
	 * @return SelectBoxService
	 */
	public function where(string $column, string $operator, string $value): SelectBoxService
	{
		$this->list = $this->list->where($column, $operator, $value);
		
		return $this;
	}


	/**
	 *
	 * @param string $orderBy
	 * @param string $sortOrder (default = 'asc')
	 * 
	 * @return SelectBoxService
	 */
	public function orderBy(string $orderBy, string $sortOrder = 'asc'): SelectBoxService
	{
		$this->list = $this->list->orderBy($orderBy, $sortOrder);

		return $this;
	}


	/**
	 * Returns the list as an Array.
	 * 
	 * @param boolean $placeHolder
	 * @param string $placeHolderText
	 * 
	 * @return array
	 */
	public function asArray(bool $placeHolder = true, string $placeHolderText = 'Please Select....'): array
	{				
		$list = $this->createList()->toArray();

		if ($placeHolder) {		
			return $this->addPlaceHolder($list, $placeHolderText);
		}

		return $list;
	}


	/**
	 * Returns the list as JSON.
	 *
	 * @param boolean $placeHolder
	 * @param string $placeHolderText
	 * 
	 * @return string (JSON)
	 */
	public function asJson(bool $placeHolder = true, string $placeHolderText = 'Please Select....'): string
	{
		$list = $this->createList()->toJson();

		if ($placeHolder) {		
			return $this->addPlaceHolder($list, $placeHolderText);
		}

		return $list;
	}


	/**
	 * Create the list.
	 *
	 * @return Collection
	 */
	protected function createList(): Collection
	{
		return $this->list->pluck($this->optionText, $this->optionValue);
	}


	/**
	 * Adds a placeholder to the list.
	 *
	 * @param array|string $list
	 * 
	 * @return array|string
	 */
	protected function addPlaceHolder(array|string $list, string $placeHolderText): array|string
	{
		$placeHolder[0] = "$placeHolderText";

		if(is_string($list)) {
			$list = json_decode($list, TRUE);
			$list = array_replace($placeHolder, $list);
			return json_encode($list);
		}
	
		return array_replace($placeHolder, $list);
	}
}
