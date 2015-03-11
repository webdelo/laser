<?php
namespace core\modules\images;
class Images extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new ImagesObject($configObject);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}


}