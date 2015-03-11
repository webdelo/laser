<?php
namespace modules\parameters\components\chooseMethods\lib;
class ChooseMethod extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new ChooseMethodObject($objectId);
		parent::__construct($object);
	}

	public function remove () {
		return $this->delete();
	}
	
}