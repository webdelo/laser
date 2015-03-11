<?php
namespace modules\catalog\basePrices\lib;
class BasePrice extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new BasePriceObject($objectId, $configObject);
		parent::__construct($object);
	}

	public function getBasePrice()
	{
		return $this->getParentObject()->price;
	}

	public function getPrice()
	{
		return $this->getObject('\modules\catalog\prices\lib\Price', $this->getParentObject()->objectId);
	}

	public function getPartner()
	{
		return $this->getObject('\modules\partners\lib\Partner', $this->getParentObject()->partnerId);
	}
}