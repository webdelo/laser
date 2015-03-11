<?php
namespace modules\components\lib;
class ComponentsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\components\lib\ComponentConfig';
	protected $objectClassName = '\modules\components\lib\Component';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
