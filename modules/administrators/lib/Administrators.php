<?php
namespace modules\administrators\lib;
class Administrators extends \core\authorization\UserRegistrator
{
	use \core\modules\statuses\StatusesTraitDecorator,
		\core\modules\groups\GroupsTraitDecorator;

	protected $configClass = '\modules\administrators\lib\AdministratorConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function getActiveManagers()
	{
		return $this->setSubquery('AND `statusId`=?d', AdministratorConfig::ACTIVE_STATUS_ID)
					->setSubquery('AND `groupId`=?d', AdministratorConfig::MANAGERS_CATEGORY_ID);
	}
}