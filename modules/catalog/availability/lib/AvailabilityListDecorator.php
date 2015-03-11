<?php
namespace modules\catalog\availability\lib;
class AvailabilityListDecorator extends \core\modules\base\ModuleDecorator
{
	private $availabilityList;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getAvailabilityList()
	{
		if (empty($this->availabilityList))
			$this->availabilityList = new AvailabilityList($this->getParentObject());
		return $this->availabilityList;
	}
}