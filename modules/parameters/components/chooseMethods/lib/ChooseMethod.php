<?php
namespace modules\parameters\components\chooseMethods\lib;
class ChooseMethod extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\parameters\components\chooseMethods\lib\ChooseMethodConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function remove () {
		return $this->delete();
	}
}