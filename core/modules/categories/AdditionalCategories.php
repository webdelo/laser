<?php
namespace core\modules\categories;
class AdditionalCategories extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new AdditionalCategoriesObject($objectId, $configObject);
		parent::__construct($object);
	}
}