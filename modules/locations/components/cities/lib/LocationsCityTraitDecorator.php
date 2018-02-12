<?php
namespace modules\locations\components\cities\lib;
trait LocationsCityTraitDecorator
{
	private $city;

	public function getLocationsCity()
	{
		if (empty($this->city)){
			$this->city = $this->cityId ? new LocationsCity($this->cityId) : $this->getNoop();
		}
		return $this->city;
	}
}
