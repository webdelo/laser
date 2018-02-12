<?php
namespace modules\locations\components\cities\lib;
class LocationsCityDecorator extends \core\modules\base\ModuleDecorator
{
	private $city;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getLocationsCity()
	{
		if (empty($this->city)){
			$this->city = $this->cityId ? new LocationsCity($this->cityId) : $this->getNoop();
		}
		return $this->city;
	}
}
