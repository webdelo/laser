<?php
namespace modules\measures\lib;
class Measures extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new MeasuresObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}
}