<?php
namespace modules\locations\components\cities\lib;
class LocationsCities extends \core\modules\base\ModuleObjects
{
	use \modules\locations\components\countries\lib\LocationsCountriesTraitDecorator;

	private $regions;

	protected $configClass = '\modules\locations\components\cities\lib\LocationsCityConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function add($data = null, $fields = array(), $rules = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::add($compacter->getPost(), $fields, $rules);
	}

	public function getLocationsRegions()
	{
		if (empty($this->regions)){
			$regions = new \modules\locations\components\regions\lib\LocationsRegions;
			$this->regions = $regions->setOrderBy('`name` ASC');
		}
		return $this->regions;
	}
}
