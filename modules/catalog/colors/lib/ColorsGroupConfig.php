<?php
namespace modules\catalog\colors\lib;
class ColorsGroupConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Alias;

	protected $objectClass = '\modules\catalog\colors\lib\ColorsGroup';
	protected $objectsClass = '\modules\catalog\colors\lib\ColorsGroups';

	protected $tablePostfix = '_colors_groups'; // set value without preffix!\
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'alias',
		'name',
		'date',
		'priority'
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			)
		);
	}

	public function _validNotEmpty($data)
	{
		return !empty($data);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

	public function _outDate($data)
	{
		return \core\utils\Dates::convertDate($data, 'simple');
	}

	public function _adaptRegDate($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : time() ;
	}

}