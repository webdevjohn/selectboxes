<?php 

namespace Webdevjohn\SelectBoxes;

use Exception;
use Illuminate\Contracts\Config\Repository;
use Webdevjohn\SelectBoxes\ModelFactory;

class SelectBoxService {
	
	public function __construct(
		protected ModelFactory $modelFactory,
		protected Repository $config
	){}


	/**
	 * Factory method that instantiates a Page class and retrieves 
	 * the select boxes for a given page.
	 *
	 * @param string $page
	 * @return object
	 */
	public function createForPage(string $page): object
	{
		$page = $this->pages($page);

		return new $page($this->modelFactory, $this->config);
	}


	/**
	 * Create a new list from a given model with fully qualified namespace.    
	 *
	 * @param string $model
	 * 
	 * @return self
	 */
	public function createFrom(string $model): self
	{
		$this->list = $this->modelFactory->make($model);

		return $this;
	}	


	/**
	 * Order the list by a given field.  The default sort order of 'ASC'
	 * can be overridden.
	 *
	 * @param string $orderBy
	 * @param string $sortOrder (default 'ASC')
	 * 
	 * @return self
	 */
	public function orderBy(string $orderBy, string $sortOrder = 'ASC'): self
	{
		$this->list = $this->list->orderBy($orderBy, $sortOrder);

		return $this;
	}


	/**
	 * The name of the field to display in the select box.
	 *
	 * @param string $field
	 * @param string $keyField
	 * 
	 * @return self
	 */
	public function display(string $field, string $keyField = 'id'): self
	{
		$this->list = $this->list->pluck($field, $keyField);

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
		$this->list = $this->list->toArray();

		if ($placeHolder) {		
			return $this->addPlaceHolder($this->list, $placeHolderText);
		}

		return $this->list;
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
		$this->list = $this->list->toJson();

		if ($placeHolder) {		
			return $this->addPlaceHolder($this->list, $placeHolderText);
		}

		return $this->list;
	}


	/**
	 * Returns a fully qualified path for a given page.
	 *
	 * @param string $page
	 * 
	 * @return string
	 */
	protected function pages(string $page): string
	{
		if ( ! $this->config->has('selectboxes')) {
			throw new Exception("Config error: The config\selectboxes.php file doesn't exist.");
		}
	
		return $this->config->get('selectboxes')[$page];	
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
