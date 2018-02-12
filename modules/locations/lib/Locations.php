<?php
namespace modules\locations\lib;
class Locations
{
	private $countriesClass = '\modules\locations\components\countries\lib\LocationsCountries';
	private $countryClass   = '\modules\locations\components\countries\lib\LocationsCountry';
	
	private $regionsClass   = '\modules\locations\components\regions\lib\LocationsRegions';
	private $regionClass    = '\modules\locations\components\regions\lib\LocationsRegion';
	
	private $citiesClass    = '\modules\locations\components\cities\lib\LocationsCities';
	private $cityClass      = '\modules\locations\components\cities\lib\LocationsCity';
	
	private $countries = array();
	private $regions   = array();
	private $cities    = array();

	public function getCountriesByAlpha2($alpha2)
	{
		return $this->getCountries()->setSubquery(' AND `alpha2` = \'?s\' ', $alpha2);
	}
	
	public function getCountriesByAlpha3($alpha3)
	{
		return $this->getCountries()->setSubquery(' AND `alpha3` = \'?s\' ', $alpha3);
	}
	
	public function getCountriesByAlphaIso($iso)
	{
		return $this->getCountries()->setSubquery(' AND `iso` = \'?s\' ', $iso);
	}
	
	public function getCountriesByAlias($alias)
	{
		return $this->getCountries()->setSubquery(' AND `alias` = \'?s\' ', $alias);
	}
	
	public function getCountries()
	{
		if ( !$this->countries )
			$this->countries = new $this->countriesClass;
		
		return $this->countries->setOrderBy('`name` ASC');
	}
	
	public function getRegionsByCountryId($countryId)
	{
		return $this->getRegions()->setSubquery(' AND `countryId` = \'?s\' ', $countryId);
	}
	
	public function getRegions()
	{
		if ( !$this->regions )
			$this->regions = new $this->regionsClass;
		return $this->regions->setOrderBy('`name` ASC');
	}
	
	public function getCitiesByCountryId($countryId)
	{
		return $this->getCities()->setSubquery(' AND `countryId` = \'?s\' ', $countryId);
	}
	
	public function getCitiesByCountryAlias($countryAlias)
	{
		return $this->getCities()->setSubquery(' AND `countryId` = ( SELECT `id` FROM `'.$this->getCountries()->mainTable().'` WHERE `alias`=\'?s\' ) ', $countryAlias);
	}
	
	public function getCitiesByAlias($cityAlias)
	{
		return $this->getCities()->setSubquery(' AND `alias` = \'?s\' ', $cityAlias);
	}
	
	public function getCitiesByRegionId($regionId)
	{
		return $this->getCities()->setSubquery(' AND `regionId` = \'?s\' ', $regionId);
	}
	
	public function getCities()
	{
		if ( !$this->cities )
			$this->cities = new $this->citiesClass;
		
		return $this->cities->setOrderBy('`name` ASC');
	}
	
	/* Start: Iterator Methods */
	function rewind() {
		reset($this->countries);
	}

	function current() {
		$this->getCountries();
		return current($this->countries);
	}

	function key() {
		return key($this->countries);
	}

	function next() {
		next($this->countries);
	}

	function valid() {
		$this->getCountries();
		return !!(current($this->countries));
	}
	/* End: Iterator Methods */
}