<?php
namespace core\modules\paymentStatuses;
trait PaymentStatusesTraitDecorator
{
	private $paymentStatuses;

	function getPaymentStatuses()
	{
	    if(empty($this->paymentStatuses))
			$this->paymentStatuses = new PaymentStatuses($this);
	    return $this->paymentStatuses;
	}
}
