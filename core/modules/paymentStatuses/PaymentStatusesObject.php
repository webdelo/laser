<?php
namespace core\modules\paymentStatuses;
class PaymentStatusesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\core\modules\paymentStatuses\PaymentStatusConfig';
	
	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
}