<?php
namespace core\modules\base;
abstract class ModuleAbstract extends \core\Model
{
	function __construct($configObject = null)
	{
		$this->setConfigObject($configObject);
		parent::__construct();
	}
	
	protected  function setConfigObject($configObject = null) {
		$this->_moduleConfig = ($this->checkConfigObject($configObject)) ? $configObject : $this->getConfigByClassName();
		$this->_moduleConfig->setModelData($this->data)
							->setModelErrors($this->errors)
							->setModelErrorList($this->errorsList);
		$configIdField = $this->_moduleConfig->getIdField();
		if (isset($configIdField))
			$this->idField = $configIdField;
		return $this;
	}
	
	private function checkConfigObject($configObject)
	{
		return ($configObject instanceof ModuleConfig);
	}
	
	private function getConfigByClassName()
	{
		$configName = '\\'.ltrim(get_class($this),'\\').'Config';
		$configObject = new $configName;
		return ($configObject instanceof ModuleConfig) ? $configObject : null;
	}
	
	public function __call($methodName, $arguments)
	{
		if (method_exists($this->_moduleConfig, $methodName))
			return call_user_func_array(array($this->_moduleConfig, $methodName), $arguments);
	}
	
	public function mainTable() 
	{
		return $this->_moduleConfig->mainTable();
	}
	
	public function rules() 
	{
		return $this->_moduleConfig->rules();	
	}
	
	public function outputRules()
	{
		return $this->_moduleConfig->outputRules();
	}
	
	protected function getRemovedStatus()
	{
		return $this->getConfig()->getRemovedStatus();
	}
	
}