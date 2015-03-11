<?php
namespace modules\paymentStatuses;
class PaymentStatusObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\paymentStatuses\PaymentStatusConfig';
	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}

}