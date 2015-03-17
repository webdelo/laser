<?php
namespace modules\locations\components\countries\lib;
class LocationsCountryDecorator extends \core\modules\base\ModuleDecorator
{
	private $country;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getLocationsCountry()
	{
		if (empty($this->country)){
			$this->country = $this->countryId ? new LocationsCountry($this->countryId) : $this->getNoop();
		}
		return $this->country;
	}
}
