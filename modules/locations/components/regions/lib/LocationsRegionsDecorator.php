<?php
namespace modules\locations\components\regions\lib;
class LocationsRegionsDecorator extends \core\modules\base\ModuleDecorator
{
	private $regions;
	private $regionsByCountry;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getLocationsRegions()
	{
		$this->regions = new \modules\locations\components\regions\lib\LocationsRegions;
		return $this->regions;
	}
	
	public function getLocationsRegionsByCountry()
	{
		if (empty($this->regionsByCountry)) {
			$this->regionsByCountry = new \modules\locations\components\regions\lib\LocationsRegions;
			$this->regionsByCountry->setSubquery(' AND `countryId` = ?d ', $this->id);
		}
		return $this->regionsByCountry;
	}
}
