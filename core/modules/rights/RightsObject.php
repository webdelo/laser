<?php
namespace core\modules\rights;
class RightsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\core\modules\rights\RightConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
}
