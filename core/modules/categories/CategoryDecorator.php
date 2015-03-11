<?php
namespace core\modules\categories;
class CategoryDecorator extends \core\modules\base\ModuleDecorator
{
	protected $category;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getCategory()
	{
		if(empty($this->category))
			$this->category = $this->getObject('\core\modules\categories\Category', $this->getParentObject()->categoryId, $this->getParentObject());
		return $this->category;
	}
}
