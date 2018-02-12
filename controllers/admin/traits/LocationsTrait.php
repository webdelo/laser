<?php
namespace controllers\admin\traits;
trait LocationsTrait
{
	private $locationsClass = '\modules\locations\lib\Locations';
	
	public function getLocationsCountries( $id = NULL )
	{
		return (new $this->locationsClass)->getCountries();
	}
	
	public function getLocationsCities( $id = NULL )
	{
		return (new $this->locationsClass)->getCountries();
	}
	
}
