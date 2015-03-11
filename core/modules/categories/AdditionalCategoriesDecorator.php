<?php
namespace core\modules\categories;
class AdditionalCategoriesDecorator extends \core\modules\base\ModuleDecorator
{
	public $additionalCategories;
	public $additionalCategoriesArray = array();
	
	function __construct($object)
	{
		parent::__construct($object);
		$this->instantAdditionalCategories()
			->instantAdditionalCategoriesArray();
	}
	
	function instantAdditionalCategories()
	{
		$this->additionalCategories = new AdditionalCategories($this->id, $this->_object);
		return $this;
	}
	
	function instantAdditionalCategoriesArray() 
	{
		if (!empty($this->additionalCategories))
			foreach($this->additionalCategories as $category)
				$this->additionalCategoriesArray[] = $category->id;
		return $this;
	}
}