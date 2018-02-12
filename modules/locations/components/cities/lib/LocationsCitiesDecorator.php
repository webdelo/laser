<?php
namespace modules\locations\components\cities\lib;
class LocationsCitiesDecorator extends \core\modules\base\ModuleDecorator
{
	private $cities;
	private $citiesByCountry;
	private $citiesByRegion;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getLocationsCities()
	{
		if (empty($this->cities))
			$this->cities = new \modules\locations\components\cities\lib\LocationsCities;
		return $this->cities;
	}
	
	public function getLocationsCitiesByCountry()
	{
		if (empty($this->citiesByCountry)) {
			$this->citiesByCountry = new \modules\locations\components\cities\lib\LocationsCities;
			$this->citiesByCountry->setSubquery(' AND `countryId`= ?d ', $this->id);
		}
		return $this->citiesByCountry;
	}
	
	public function getLocationsCitiesByRegion()
	{
		if (empty($this->citiesByRegion)) {
			$this->citiesByRegion = new \modules\locations\components\cities\lib\LocationsCities;
			$this->citiesByRegion->setSubquery(' AND `regionId`= ?d ', $this->id);
		}
		return $this->citiesByRegion;
	}
}
