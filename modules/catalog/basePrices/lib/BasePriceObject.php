<?php
namespace modules\catalog\basePrices\lib;
class BasePriceObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\catalog\basePrices\lib\BasePriceConfig';
	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}
}