<?php
namespace modules\deliveries\lib;
class DeliveryDecorator extends \core\modules\base\ModuleDecorator
{
	private $delivery;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getDelivery()
	{
	    if(empty($this->delivery)){
			$this->delivery = ($this->deliveryId) ? new Delivery($this->deliveryId) : $this->getNoop();
	    }

	    return $this->delivery;
	}
}
