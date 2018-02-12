<?php
namespace core\modules\paymentStatuses;
class PaymentStatus extends \core\modules\base\ModuleDecorator
{	
	function __construct($objectId, $configObject)
	{
		$object = new PaymentStatusObject($objectId, $configObject);
		parent::__construct($object);
	}
}