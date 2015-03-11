<?php
namespace modules\catalog\devices\lib;
class DeviceObject extends \modules\catalog\CatalogGoodObject
{
	protected $configClass = '\modules\catalog\devices\lib\DeviceConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}