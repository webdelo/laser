<?php
namespace modules\addresses\lib;
trait AddressTraitDecorator
{
	private $adressObject;

	public function getAddress()
	{
		if(empty($this->adressObject))
			$this->adressObject = $this->addressId ? new \modules\addresses\lib\Address($this->addressId, $this) : $this->getNoop();
		return $this->adressObject;
	}
}
