<?php
namespace modules\catalog\colors\lib;
class ColorsGroupsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\catalog\colors\lib\ColorsGroupConfig';
	protected $objectClassName = '\modules\catalog\colors\lib\ColorsGroup';
	
	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
}