<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValueConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Base,
		\core\traits\adapters\Priority;

	protected $objectClass  = '\modules\properties\components\propertiesValues\lib\PropertyValue';
	protected $objectsClass = '\modules\properties\components\propertiesValues\lib\PropertyValues';

	public $templates  = 'modules/properties/tpl/';

	protected $table = 'properties_values'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'value',
		'description',
		'priority',
		'propertyId',
		'measureCategoryId',
	);

	public function rules()
	{
		return array(
			'value' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'propertyId' => array(
				'validation' => array('_validInt'),
			),
			'measureCategoryId' => array(
				'validation' => array('_validInt'),
				'adapt' => '_adaptMeasureCategoryDefault',
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'description' => array(
				'adapt' => '_adaptHtml',
			),
			'priority' => array(
				'adapt' => '_adaptPriority',
			)
		);
	}
	
	public function _adaptMeasureCategoryDefault($key)
	{
		if (!isset($this->data[$key])) {
			$this->data[$key] = 4;
		}
	}


	public function relations()
	{
		$relations = array(
			'tbl_realties_properties_values_relation' => array('idField'=>'propertyValueId'),
		);
		return $relations;
	}

}
