<?php
namespace modules\modulesDomain\lib;
class ModulesDomain extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ModulesDomainObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}
}