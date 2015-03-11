<?php
namespace modules\paymentStatuses;
class PaymentStatuses extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new PaymentStatusesObject($configObject);
		parent::__construct($object);
	}
}