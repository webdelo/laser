<?php
namespace modules\addresses\lib;
class Address extends \core\modules\base\ModuleObject
{
	use \modules\locations\components\cities\lib\LocationsCityTraitDecorator;

	protected $configClass = '\modules\addresses\lib\AddressConfig';

	function __construct($objectId, $configObject)
	{
	    parent::__construct($objectId, new $this->configClass($configObject));
	}

	public function getAddressString()
	{

		$block  = $this->block?'/'. $this->block:'';
		$region = ( (string)$this->getLocationsCity()->getLocationsRegion()->getName() == (string)$this->getLocationsCity()->getLocationsCountry()->getName() )
				   ||
				  ( (string)$this->getLocationsCity()->getLocationsRegion()->getName() == (string)$this->getLocationsCity()->getName() )
					? ''
					: $this->getLocationsCity()->getLocationsRegion()->getName();

		$house = ($this->home) ? 'д.'. $this->home . $block : '';
		$addressString = $this->getLocationsCity()->getLocationsCountry()->getName()
			.' '. $region
			.' '. $this->getLocationsCity()->getName()
			.' '. $this->street
			.' '. $house
			.' '. $this->flat;

		return $addressString;
	}

	public function getAddressShort()
	{

		$block  = $this->block?'/'. $this->block:'';
		$region = ( (string)$this->getLocationsCity()->getLocationsRegion()->getName() == (string)$this->getLocationsCity()->getLocationsCountry()->getName() )
				   ||
				  ( (string)$this->getLocationsCity()->getLocationsRegion()->getName() == (string)$this->getLocationsCity()->getName() )
					? ''
					: $this->getLocationsCity()->getLocationsRegion()->getName();

		$addressString = $this->getLocationsCity()->getLocationsCountry()->getName()
						.' '. $region
						.' '. $this->getLocationsCity()->getName();

		return $addressString;
	}

	public function getCoordinates()
	{
		return $this->latitude.', '.$this->longitude;
	}

	public function hasCoordinates()
	{
		return $this->latitude || $this->longitude;
	}

	public function isAccept()
	{
		return ($this->street || $this->hasCoordinates());
	}
}