<?php
namespace modules\catalog\categories;
class CatalogCategoryDecorator extends \core\modules\base\ModuleDecorator
{
	protected $category;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getCategory()
	{
		if(empty($this->category))
			$this->category = $this->getObject('\modules\catalog\categories\CatalogCategory', $this->getParentObject()->categoryId, $this->getParentObject());
		return $this->category;
	}
}
