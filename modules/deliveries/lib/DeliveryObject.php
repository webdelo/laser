<?php
namespace modules\deliveries\lib;
class DeliveryObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\deliveries\lib\DeliveryConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}