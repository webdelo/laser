<?php
namespace core\modules\statuses;
class TranslateStatuses extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\core\modules\statuses\TranslateStatusConfig';

	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}

}