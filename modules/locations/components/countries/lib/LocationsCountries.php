<?php
namespace modules\locations\components\countries\lib;
class LocationsCountries extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\locations\components\countries\lib\LocationsCountryConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function add($data = null, $fields = array(), $rules = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::add($compacter->getPost(), $fields, $rules);
	}
}
