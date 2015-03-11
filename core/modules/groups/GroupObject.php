<?php
namespace core\modules\groups;
class GroupObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\core\modules\groups\GroupConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass());
	}

}