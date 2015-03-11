<?php
namespace modules\catalog\categories;
class CatalogCategories extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new CatalogCategoriesObject($configObject);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}