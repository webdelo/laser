<?php
namespace modules\properties\components\propertiesValues\lib;
class RelationsConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Base;

	protected $objectClass  = '\core\modules\relations\Relation';
	protected $objectsClass = '\modules\properties\components\propertiesValues\lib\Relations';

	public $objectDecorators = array(
		'\modules\properties\components\propertiesValues\lib\PropertyValueDecorator',
		'\modules\measures\lib\MeasureDecorator'
	);

	protected $tablePostfix = '_properties_values_relation'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'ownerId',
		'propertyValueId',
		'value',
		'measureId',
	);

	public function rules()
	{
		return array(
			'value' => array(
				'validation' => array('_deleteIfEmpty'),
				'adapt' => '_adaptHtml',
			)
		);
	}

	public function _deleteIfEmpty($data)
	{
		if($data === 0)
			return false;
		return empty($data) ? !\core\db\Db::getMysql()->query(' DELETE FROM `'.$this->mainTable().'` WHERE `id` = ?d ', array($this->data['id'])) : true;
	}

}
