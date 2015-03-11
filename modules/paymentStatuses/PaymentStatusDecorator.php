<?php
namespace modules\paymentStatuses;
class PaymentStatusDecorator extends \core\modules\base\ModuleDecorator
{
	public $paymentStatus;
	
	function __construct($object)
	{
		parent::__construct($object);
	}
	
	function getPaymentStatus()
	{
		if ($this->getParentObject()->paymentStatusId) {
			if(empty($this->paymentStatus))
				$this->paymentStatus = $this->getObject('\modules\paymentStatuses\PaymentStatus', $this->getParentObject()->paymentStatusId, $this);
		} else {
			$this->paymentStatus = $this->getNoop();
		}			
	    
	    return $this->paymentStatus;
	}
}