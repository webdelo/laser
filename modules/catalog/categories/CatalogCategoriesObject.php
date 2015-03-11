<?php
namespace modules\catalog\categories;
class CatalogCategoriesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\catalog\categories\CatalogCategoryConfig';
	
	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
}