<?php
namespace modules\fabricators\lib;
class FabricatorsDecorator extends \core\modules\base\ModuleDecorator
{

	protected $fabricators;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getFabricators()
	{
	    if(empty($this->fabricators))
		$this->fabricators = new \modules\fabricators\lib\Fabricators();

	    return $this->fabricators;
	}
}
