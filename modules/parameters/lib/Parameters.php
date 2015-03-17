<?php
namespace modules\parameters\lib;
class Parameters extends \core\modules\base\ModuleObjects
{
	use \core\modules\statuses\StatusesTraitDecorator,
		\core\modules\categories\CategoriesTraitDecorator;

	protected $configClass     = '\modules\parameters\lib\ParameterConfig';
	protected $objectClassName = '\modules\parameters\lib\Parameter';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function getParametersByCategoryId($categoryId)
	{
		$this->setSubquery( ' AND `id` IN ( SELECT `ownerId` FROM `'.$this->mainTable().'_additional_categories` WHERE `objectId` = ?d ) ', (int)$categoryId);
		return $this;
	}
	public function getParametersByCategoryAlias($alias)
	{
		$this->setSubquery( ' AND `id` IN ( SELECT `ownerId` FROM `'.$this->mainTable().'_additional_categories` WHERE `objectId` = ( SELECT `id` FROM `'.$this->mainTable().'_categories` WHERE `alias`=\'?s\' ) ) ', $alias);
		return $this;
	}
}