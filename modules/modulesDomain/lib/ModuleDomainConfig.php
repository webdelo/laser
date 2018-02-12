<?php
namespace modules\modulesDomain\lib;
class ModuleDomainConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;
	const STATUS_DELETED = 3;

	public $constructionsId = 9;
	public $devicesId = 21;

	protected $objectClass  = '\modules\modulesDomain\lib\ModuleDomain';
	protected $objectsClass = '\modules\modulesDomain\lib\ModulesDomain';

	public $templates  = 'modules/modulesDomain/tpl/';

	protected $table = 'modules_domain'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'priority',
		'name',
		'alias',
		'description',
		'date',
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
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'categoryId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

}
