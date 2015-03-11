<?php
namespace modules\deliveries\lib;
class DeliveriesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\deliveries\lib\DeliveryConfig';
	protected $objectClassName = '\modules\deliveries\lib\Delivery';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
