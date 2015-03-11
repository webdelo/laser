<?php
namespace modules\measures\lib;
class MeasureObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\measures\lib\MeasureConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}