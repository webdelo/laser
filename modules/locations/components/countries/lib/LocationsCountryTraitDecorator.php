<?php
namespace modules\locations\components\countries\lib;
trait LocationsCountryTraitDecorator
{
	private $country;

	public function getLocationsCountry()
	{
		if (empty($this->country)){
			$this->country = $this->countryId ? new LocationsCountry($this->countryId) : $this->getNoop();
		}
		return $this->country;
	}
}
