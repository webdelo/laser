<?php
namespace modules\parameters\components\chooseMethods\lib;
class ChooseMethodsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\parameters\components\chooseMethods\lib\ChooseMethodConfig';
	protected $objectClassName = '\modules\parameters\components\chooseMethods\lib\ChooseMethod';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
