<?php
namespace modules\fabricators\lib;
class FabricatorsObject extends \core\modules\base\ModuleObjects
{
        use \core\traits\objects\WordsSearch;

	protected $configClass     = '\modules\fabricators\lib\FabricatorConfig';
	protected $objectClassName = '\modules\fabricators\lib\Fabricator';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
