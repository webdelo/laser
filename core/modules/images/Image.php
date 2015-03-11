<?php
namespace core\modules\images;
class Image extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{	
		$object = new ImageObject($objectId, $configObject);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\categories\CategoryDecorator($object);
		parent::__construct($object);
	}
}