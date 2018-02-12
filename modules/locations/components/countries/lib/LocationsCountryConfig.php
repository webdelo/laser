<?php
namespace modules\locations\components\countries\lib;
class LocationsCountryConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Base,
		\core\traits\validators\Alias;

	protected $bulgaryId = 428;

	protected $objectClass = '\modules\locations\components\countries\lib\LocationsCountry';
	protected $objectsClass = '\modules\locations\components\countries\lib\LocationsCountries';

	public $templates  = 'modules/locations/components/countries/tpl/';

	protected $table = 'locations_countries'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'name',
		'alias',
		'alpha2',
		'alpha3',
		'iso'
	);

	public function rules()
	{
		return array(
			'alpha2, alpha3' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'validation' => array('_validAlias'),
			),
			'iso' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
		);
	}

	public function getBulgaryCountryId()
	{
		return $this->bulgaryId;
	}
}
