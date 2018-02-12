<?php
namespace modules\locations\components\regions\lib;
class LocationsRegionDecorator extends \core\modules\base\ModuleDecorator
{
	private $region;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getLocationsRegion()
	{
		if (empty($this->region)){
			$this->region = $this->regionId ? new LocationsRegion($this->regionId) : $this->getNoop() ;
		}
		return $this->region;
	}
}
