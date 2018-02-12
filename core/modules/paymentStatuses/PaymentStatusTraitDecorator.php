<?php
/* Requires \core\traits\ObjectPool in parent object */
namespace core\modules\paymentStatuses;
trait PaymentStatusTraitDecorator
{
	private $paymentStatus;

	function getPaymentStatus()
	{
		if ($this->paymentStatusId) {
			if(empty($this->paymentStatus))
				$this->paymentStatus = $this->getObject('\core\modules\paymentStatuses\PaymentStatus', $this->paymentStatusId, $this);
		} else {
			$this->paymentStatus = $this->getNoop();
		}
	    
	    return $this->paymentStatus;
	}
}