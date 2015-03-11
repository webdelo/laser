<?php
namespace core\modules\categories;
class CategoriesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\core\modules\categories\CategoryConfig';
	
	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
}