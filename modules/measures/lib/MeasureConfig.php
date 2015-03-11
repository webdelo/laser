<?php
namespace modules\measures\lib;
class MeasureConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;
	const REMOVED_STATUS_ID = 3;

	protected $objectClass = '\modules\measures\lib\Measure';
	protected $objectsClass = '\modules\measures\lib\Measures';

	public $templates  = 'modules/measures/tpl/';

	protected $table = 'measures'; // set value without preffix!
	protected $idField = 'id';
	protected $removedStatus = 3;
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'priority',
		'name',
		'shortName',
		'declension1',
		'declension2',
		'declension3',
		'alias',
		'description',
		'date'
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'declension1,declension2,declension3' => array(
				'adapt' => '_adaptHtml',
			),
			'statusId' => array(
				'validation' => array('_validInt', array('not_empty'=>true)),
			),
			'categoryId' => array(
				'validation' => array('_validInt', array('not_empty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			)
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}
	
	public function _adaptDiclension($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : '' ;
	}

}
