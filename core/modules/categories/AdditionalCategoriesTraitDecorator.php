<?php
namespace core\modules\categories;
trait AdditionalCategoriesTraitDecorator
{
	private $additionalCategories;
	private $additionalCategoriesArray = array();

	function getAdditionalCategories()
	{
		if (empty($this->additionalCategories))
			$this->additionalCategories = new AdditionalCategories($this->id, $this);
		return $this->additionalCategories;
	}

	function getAdditionalCategoriesArray()
	{
		if (empty($this->additionalCategoriesArray))
			foreach($this->getAdditionalCategories() as $category)
				$this->additionalCategoriesArray[] = $category->id;
		return $this->additionalCategoriesArray;
	}
}