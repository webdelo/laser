<?php
namespace modules\locations\components\cities\lib;
class LocationsCityConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\validators\Alias;

	protected $objectClass = '\modules\locations\components\cities\lib\LocationsCity';
	protected $objectsClass = '\modules\locations\components\cities\lib\LocationsCities';

	public $templates  = 'modules/locations/components/cities/tpl/';

	protected $table = 'locations_cities'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'name',
		'alias',
		'countryId',
		'regionId'
	);

	public function rules()
	{
		return array(
			'alias' => array(
				'validation' => array('_validAlias'),
			),
			'countryId, regionId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
		);
	}
}
