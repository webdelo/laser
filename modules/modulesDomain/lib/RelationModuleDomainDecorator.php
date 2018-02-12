<?php
namespace modules\modulesDomain\lib;
class RelationModuleDomainDecorator extends \core\modules\base\ModuleDecorator
{
	public $modulesDomain;
	public $modulesDomainArray = array();
	
	function __construct($object)
	{
		parent::__construct($object);
		$this->instantModulesDomain()
		     ->instantModulesDomainArray();
	}
	
	function instantModulesDomain()
	{
		$this->modulesDomain = new RelationModuleDomain($this->id, $this->_object);
		return $this;
	}
	
	function instantModulesDomainArray()
	{
		if (!empty($this->modulesDomain))
			foreach($this->modulesDomain as $moduleDomain) {
				$this->modulesDomainArray[] = $moduleDomain->id;
			}
		return $this;
	}
}