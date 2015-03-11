<?php
namespace modules\components\lib;
class Components extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ComponentsObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}