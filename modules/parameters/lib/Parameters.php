<?php
namespace modules\parameters\lib;
class Parameters extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ParametersObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
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
	
	public function getParametersById($id)
	{
		if(!is_array($id))
			$id = array($id);
		return $this->setSubquery('AND `id` IN (?s)',  implode(',', $id));
	}
}