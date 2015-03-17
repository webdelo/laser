<?php
namespace modules\locations\components\regions\lib;
class LocationsRegionConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\validators\Alias;

	protected $objectClass = '\modules\locations\components\regions\lib\LocationsRegion';
	protected $objectsClass = '\modules\locations\components\regions\lib\LocationsRegions';

	public $templates  = 'modules/locations/components/regions/tpl/';

	protected $table = 'locations_regions'; // set value without preffix!

	protected $idField = 'id';

	protected $objectFields = array(
		'id',
		'countryId',
		'name',
		'alias'
	);

	public function rules()
	{
		return array(
			'countryId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'alias' => array(
				'validation' => array('_validAlias'),
			),
		);
	}

}
