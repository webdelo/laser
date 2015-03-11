<?php
namespace modules\catalog\constructions\lib;
class Constructions extends \core\modules\base\ModuleDecorator implements \Countable
{
	use \modules\catalog\traits\filterCategoryAlias;

	function __construct()
	{
		$object = new ConstructionsObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}

	/* Start: Countable Methods */
	public function count()
	{
		return $this->getParentObject()->count();
	}
	/* End: Countable Methods */

	public function getConstructionsByCategoryId($categoryId)
	{
		$this->resetFilters()
			->setSubquery('AND `categoryId` =?d', $categoryId)
			->setOrderBy('`priority` ASC, `date` ASC, `id` ASC');
		return $this;
	}
}
