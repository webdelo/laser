<?php
namespace modules\fabricators\lib;
class FabricatorObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\fabricators\lib\FabricatorConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}