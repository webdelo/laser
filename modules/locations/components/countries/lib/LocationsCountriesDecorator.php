<?php
namespace modules\locations\components\countries\lib;
class LocationsCountriesDecorator extends \core\modules\base\ModuleDecorator
{
	private $countries;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getLocationsCountries()
	{
		if (empty($this->countries))
			$this->countries = new \modules\locations\components\countries\lib\LocationsCountries;
		return $this->countries;
	}
}
