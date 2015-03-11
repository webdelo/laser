<?php
namespace modules\properties\lib;
class Properties extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new PropertiesObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}
	
	public function getPropertiesById($id)
	{
		if(!is_array($id))
			$id = array($id);
		return $this->setSubquery('AND `id` IN (?s)',  implode(',', $id));
	}
}