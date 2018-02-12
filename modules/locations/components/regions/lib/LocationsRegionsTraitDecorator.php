<?php
namespace modules\locations\components\regions\lib;
trait LocationsRegionsTraitDecorator
{
	private $regions;
	private $regionsByCountry;

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
