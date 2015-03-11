<?php
namespace modules\measures\lib;
class MeasuresObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\measures\lib\MeasureConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
