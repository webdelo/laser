<?php
namespace modules\catalog\availability\lib;
class Availability extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new AvailabilityObject($objectId, $configObject);
		$object = new \core\authorization\UserDecorator($object, $object->userId);
		parent::__construct($object);
	}

	public function getQuantity()
	{
		return $this->getParentObject()->quantity;
	}

	public function isManufacturer()
	{
		return !!$this->getParentObject()->manufacturer;
	}

	public function getComment()
	{
		return $this->getParentObject()->comment;
	}

	public function getLastUpdateTime()
	{
		return $this->getParentObject()->lastUpdate;
	}

	public function getPartner()
	{
		return $this->getObject('\modules\partners\lib\Partner', $this->getParentObject()->partnerId);
	}
}