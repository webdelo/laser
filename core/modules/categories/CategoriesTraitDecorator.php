<?php
namespace core\modules\categories;
trait CategoriesTraitDecorator
{
	private $categories;
	private $mainCategories;

	public function getCategories()
	{
	    if(empty($this->categories)){
			$this->categories = new Categories($this);
			$this->categories->setOrderBy('`priority` ASC');
	    }

	    return $this->categories;
	}

	public function getMainCategories($statusesArray = array())
	{
		if (!is_array($statusesArray))
			$statusesArray = array((int)$statusesArray);
		if(empty($this->mainCategories)){
			$this->mainCategories = new Categories($this);
			$this->mainCategories->setSubquery('AND `parentId`=?d', 0)
								 ->setOrderBy('`priority` ASC');
			if(!empty($statusesArray))
				$this->mainCategories->setSubquery(' AND `statusId` IN (?s)',  implode(',', $statusesArray));
		}

		return $this->mainCategories;
	}
}