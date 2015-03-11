<?php
namespace modules\parameters\components\chooseMethods\lib;
class ChooseMethods extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ChooseMethodsObject();
		parent::__construct($object);
	}
}