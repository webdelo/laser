<?php
namespace modules\fabricators\lib;
class Fabricators extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new FabricatorsObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}

	protected function getActiveFabricators()
	{
		$config = $this->getConfig();
		return $this->setSubquery('AND `statusId`=?d', $config::ACTIVE_STATUS_ID);
	}
}