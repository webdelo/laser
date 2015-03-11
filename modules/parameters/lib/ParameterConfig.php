<?php
namespace modules\parameters\lib;
class ParameterConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;

	protected $objectClass  = '\modules\parameters\lib\Parameter';
	protected $objectsClass = '\modules\parameters\lib\Parameters';

	public $templates  = 'modules/parameters/tpl/';

	protected $table = 'parameters'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'statusId',
		'name',
		'alias',
		'chooseMethodId',
		'description',
		'priority',
		'date',
		'imagePath'
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
			'statusId' => array(
				'adapt' => '_adaptStatus',
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'description' => array(
				'adapt' => '_adaptHtml',
			),
			'imagePath' => array(
				'adapt' => '_adaptHtml',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
		);
	}

	public function relations()
	{
		$relations = array();
		return $relations;
	}
}
