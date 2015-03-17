<?php
namespace modules\properties\lib;
class Properties extends \core\modules\base\ModuleObjects
{
	use \core\modules\statuses\StatusesTraitDecorator,
		\core\modules\categories\CategoriesTraitDecorator;

	protected $configClass     = '\modules\properties\lib\PropertyConfig';
	protected $objectClassName = '\modules\properties\lib\Property';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
