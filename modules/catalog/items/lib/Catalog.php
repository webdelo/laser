<?php
namespace modules\catalog\items\lib;
class Catalog extends \core\modules\base\ModuleDecorator implements \Countable
{
	use \modules\catalog\traits\filterCategoryAlias;

	function __construct()
	{
		$object = new CatalogObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		$object = new \modules\fabricators\lib\FabricatorsDecorator($object);
		parent::__construct($object);
	}

	/* Start: Countable Methods */
	public function count()
	{
		return $this->getParentObject()->count();
	}
	/* End: Countable Methods */

	public function getMinPrice()
	{
		return $this->getMinField('price', 'tbl_catalog_items_prices');
	}

	public function getMaxPrice()
	{
		return $this->getMaxField('price', 'tbl_catalog_items_prices');
	}

	public function getFabricatorsByMainCategoriesId($mainCategoriesId = null)
	{
		if(!isset($mainCategoriesId)  ||  empty($mainCategoriesId))
			return $this->getObject('\modules\fabricators\lib\Fabricators')
					->getActiveFabricators();

		$fabricatorsIdArray = array();
		$this->setSubquery('AND `categoryId` IN ( SELECT `id` FROM `tbl_catalog_items_categories` WHERE `parentId` IN (?s))', $mainCategoriesId);
		foreach($this as $object)
			if(!in_array($object->fabricatorId, $fabricatorsIdArray))
				$fabricatorsIdArray[] = $object->fabricatorId;

		return $this->getObject('\modules\fabricators\lib\Fabricators')
				->setSubquery('AND `id` IN (?s)', implode(',', $fabricatorsIdArray));

	}
}
