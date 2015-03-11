<?php
namespace modules\paymentStatuses;
class PaymentStatusesDecorator extends \core\modules\base\ModuleDecorator
{

	protected $paymentStatuses;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getPaymentStatuses()
	{
	    if(empty($this->paymentStatuses))
		$this->paymentStatuses = new PaymentStatuses($this->getParentObject());

	    return $this->paymentStatuses;
	}
}
