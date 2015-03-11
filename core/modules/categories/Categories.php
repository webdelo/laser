<?php
namespace core\modules\categories;
class Categories extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new CategoriesObject($configObject);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}