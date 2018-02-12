<?php
namespace core\modules\categories;
trait TranslateCategoriesTraitDecorator
{
	private $categories;
	private $mainCategories;

	public function getCategories()
	{
	    if(empty($this->categories)){
			$this->categories = new TranslateCategories($this);
			$this->categories->setOrderBy('`priority` ASC');
			$this->addLangObserver($this->categories);
	    }

	    return $this->categories;
	}

	public function getMainCategories($statusesArray = array())
	{
		if (!is_array($statusesArray))
			$statusesArray = array((int)$statusesArray);
		if(empty($this->mainCategories)){
			$this->mainCategories = new TranslateCategories($this);
			$this->addLangObserver($this->mainCategories);
			$this->mainCategories->setSubquery('AND `parentId`=?d', 0)
								 ->setOrderBy('`priority` ASC');
			if(!empty($statusesArray))
				$this->mainCategories->setSubquery(' AND `statusId` IN (?s)',  implode(',', $statusesArray));
		}

		return $this->mainCategories;
	}
}