<?php
/* Requires \core\traits\ObjectPool in parent object */
namespace core\modules\categories;
trait TranslateCategoryTraitDecorator
{
	protected $category;

	public function getCategory()
	{
		$this->checkCategoryTraitsRequires();
		if(empty($this->category)){
			$this->category = $this->getObject('\core\modules\categories\TranslateCategory', $this->categoryId, $this);
			$this->category->setLang($this->getLang());
			$this->addLangObserver($this->category);
		}
		return $this->category;
	}

	private function checkCategoryTraitsRequires()
	{
		if (in_array('getObject', get_class_methods($this)))
			return $this;
		throw new \Exception('Requires implementation of the method "getObject" for trait "\core\modules\categories\CategoryTraitDecorator" in object '.get_class($this).'!');
	}
}
