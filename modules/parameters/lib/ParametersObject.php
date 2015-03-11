<?php
namespace modules\parameters\lib;
class ParametersObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\parameters\lib\ParameterConfig';
	protected $objectClassName = '\modules\parameters\lib\Parameter';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
