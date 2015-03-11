<?php
namespace modules\modulesDomain\lib;
class ModuleDomainDecorator extends \core\modules\base\ModuleDecorator
{
	private $module;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getModule()
	{
	    if(empty($this->module)){
			$this->module = $this->moduleId ? new ModuleDomain($this->moduleId) : $this->getNoop();
	    }

	    return $this->module;
	}
}
