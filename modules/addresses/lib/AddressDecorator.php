<?php
namespace modules\addresses\lib;
class AddressDecorator extends \core\modules\base\ModuleDecorator
{
	protected $adressObject;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getAddress()
	{
		if(empty($this->adressObject))
			$this->adressObject = $this->getParentObject()->addressId ? new \modules\addresses\lib\Address($this->getParentObject()->addressId, $this->getParentObject()) : $this->getNoop();
		return $this->adressObject;
	}
}
