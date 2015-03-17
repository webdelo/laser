<?php
/* Requires \core\traits\ObjectPool in parent object */
namespace core\modules\categories;
trait CategoryTraitDecorator
{
	protected $category;

	public function getCategory()
	{
		$this->checkCategoryTraitsRequires();
		if(empty($this->category))
			$this->category = $this->getObject('\core\modules\categories\Category', $this->categoryId, $this);
		return $this->category;
	}

	private function checkCategoryTraitsRequires()
	{
		if (in_array('getObject', get_class_methods($this)))
			return $this;
		throw new \Exception('Requires implementation of the method "getObject" for trait "\core\modules\categories\CategoryTraitDecorator" in object '.get_class($this).'!');
	}
}
