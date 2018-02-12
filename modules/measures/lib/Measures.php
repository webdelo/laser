<?php
namespace modules\measures\lib;
class Measures extends \core\modules\base\ModuleObjects
{
	use \core\modules\statuses\StatusesTraitDecorator,
		\core\modules\categories\CategoriesTraitDecorator;

	protected $configClass     = '\modules\measures\lib\MeasureConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
