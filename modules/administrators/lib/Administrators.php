<?php
namespace modules\administrators\lib;
class Administrators extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new AdministratorsObject;
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\groups\GroupsDecorator($object);
		parent::__construct($object);
	}

	protected function getActiveManagers()
	{
		$administrators = new AdministratorsObject;
		$config = $administrators->getConfig();
		return $administrators->setSubquery('AND `statusId`=?d', $config::ACTIVE_STATUS_ID)
						->setSubquery('AND `groupId`=?d', $config::MANAGERS_CATEGORY_ID);
	}
}