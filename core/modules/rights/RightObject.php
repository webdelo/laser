<?php
namespace core\modules\rights;
class RightObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\core\modules\rights\RightConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass());
	}

}