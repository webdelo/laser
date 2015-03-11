<?php
namespace modules\paymentStatuses;
class PaymentStatusesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\paymentStatuses\PaymentStatusConfig';
	
	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
}