<?php
namespace modules\locations\components\regions\lib;
trait LocationsRegionTraitDecorator
{
	private $region;

	public function getLocationsRegion()
	{
		if (empty($this->region)){
			$this->region = $this->regionId ? new LocationsRegion($this->regionId) : $this->getNoop() ;
		}
		return $this->region;
	}
}
