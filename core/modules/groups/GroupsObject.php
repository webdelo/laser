<?php
namespace core\modules\groups;
class GroupsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\core\modules\groups\GroupConfig';

	function __construct()
	{
		\core\modules\base\ModuleObjects::__construct(new $this->configClass);
	}
}
