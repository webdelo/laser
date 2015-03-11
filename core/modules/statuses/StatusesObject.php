<?php
namespace core\modules\statuses;
class StatusesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\core\modules\statuses\StatusConfig';
	
	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
}