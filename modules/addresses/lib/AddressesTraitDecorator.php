<?php
namespace modules\addresses\lib;
trait AddressesTraitDecorator
{
	private $addresses;

	public function getAddresses()
	{
	    if(empty($this->addresses)){
			$this->addresses = new Addresses($this);
			$this->addresses->setOrderBy('`priority` ASC');
	    }
	    return $this->addresses;
	}
}
