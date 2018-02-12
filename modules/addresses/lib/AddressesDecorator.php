<?php
namespace modules\addresses\lib;
class AddressesDecorator extends \core\modules\base\ModuleDecorator
{
	private $addresses;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getAddresses()
	{
	    if(empty($this->addresses)){
			$this->addresses = new Addresses($this->getParentObject());
			$this->addresses->setOrderBy('`priority` ASC');
	    }

	    return $this->addresses;
	}
}
