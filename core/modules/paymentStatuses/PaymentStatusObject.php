<?php
namespace core\modules\paymentStatuses;
class PaymentStatusObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\core\modules\paymentStatuses\PaymentStatusConfig';
	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}

}