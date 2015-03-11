<?php
namespace modules\parameters\components\chooseMethods\lib;
class ChooseMethodDecorator extends \core\modules\base\ModuleDecorator
{
	public $chooseMethod;
	
	function __construct($object)
	{
		parent::__construct($object);
	}
	
	public function getChooseMethod()
	{
		if ( !$this->chooseMethod ) {
			$this->chooseMethod = $this->chooseMethodId ? new \modules\parameters\components\chooseMethods\lib\ChooseMethod($this->chooseMethodId) : $this->getNoop();
		}
		return $this->chooseMethod;
	}
}