<?php
namespace modules\locations\components\regions\lib;
class LocationsRegions extends \core\modules\base\ModuleObjects
{
	use \modules\locations\components\countries\lib\LocationsCountriesTraitDecorator;

	protected $configClass = '\modules\locations\components\regions\lib\LocationsRegionConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function add($data = null, $fields = array(), $rules = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::add($compacter->getPost(), $fields, $rules);
	}

	public function getRegionsByCountryId($countryId)
	{
		return $this->setSubquery('AND `countryId` = ?d', $countryId)
				->setOrderBy('`name` ASC');
	}
}
