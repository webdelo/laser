<?php
namespace modules\catalog\categories;
class CatalogCategoriesDecorator extends \core\modules\base\ModuleDecorator
{
	private $categories;
	private $mainCategories;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getCategories()
	{
	    if(empty($this->categories)){
			$this->categories = new CatalogCategories($this->getParentObject());
			$this->categories->setOrderBy('`priority` ASC');
	    }

	    return $this->categories;
	}

	public function getMainCategories($statusesArray = array())
	{
		if (!is_array($statusesArray))
			$statusesArray = array((int)$statusesArray);
	    if(empty($this->mainCategories)){
			$this->mainCategories = new CatalogCategories($this->getParentObject());
			$this->mainCategories->setSubquery('AND `parentId`=?d', 0)
							->setOrderBy('`priority` ASC');
			if(!empty($statusesArray))
				$this->mainCategories->setSubquery(' AND `statusId` IN (?s)',  implode(',', $statusesArray));
	    }

	    return $this->mainCategories;
	}
}
