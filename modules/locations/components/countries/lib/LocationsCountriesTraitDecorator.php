<?php
namespace modules\locations\components\countries\lib;
trait LocationsCountriesTraitDecorator
{
	private $countries;

	public function getLocationsCountries()
	{
		if (empty($this->countries)){
			$countries = new \modules\locations\components\countries\lib\LocationsCountries;
			$this->countries = $countries->setOrderBy('`name` ASC');
		}
		return $this->countries;
	}
}