<?php
namespace modules\catalog\devices\lib;
class Devices extends \core\modules\base\ModuleDecorator implements \Countable
{
	use \modules\catalog\traits\filterCategoryAlias;

	function __construct()
	{
		$object = new DevicesObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \modules\catalog\categories\CatalogCategoriesDecorator($object);
		$object = new \modules\catalog\colors\lib\ColorsGroupsDecorator($object);
		parent::__construct($object);
	}

	/* Start: Countable Methods */
	public function count()
	{
		return $this->getParentObject()->count();
	}
	/* End: Countable Methods */

	public function getDevicesByCategoryId($categoryId)
	{
		$this->resetFilters()
			->setSubquery('AND `categoryId` =?d', $categoryId)
			->setOrderBy('`date` DESC, `id` DESC');
		return $this;
	}
}
