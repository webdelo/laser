<?php
namespace core\modules\groups;
class Groups extends \core\modules\base\ModuleObjects
{
	use \core\modules\statuses\StatusesTraitDecorator;

	protected $configClass = '\core\modules\groups\GroupConfig';

	function __construct()
	{
		\core\modules\base\ModuleObjects::__construct(new $this->configClass);
	}
}
