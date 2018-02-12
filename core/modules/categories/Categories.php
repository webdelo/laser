<?php
namespace core\modules\categories;
class Categories extends \core\modules\base\ModuleObjects
{
	use \core\modules\statuses\StatusesTraitDecorator;
	protected $configClass = '\core\modules\categories\CategoryConfig';

	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}

}