<?php
namespace modules\console\lib;
class ConsoleItemConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	protected $objectClass  = '\modules\console\lib\ConsoleItem';
	protected $objectsClass = '\modules\console\lib\Console';
	
	public $newStatus      = 1;
	public $viewedStatus   = 2;
	public $archivedStatus = 3;
	
	public $importantCategory = 1;
	public $simpleCategory    = 2;

	public $templates  = 'modules/console/tpl/';
	public $imagesPath = 'files/console/images/';
	public $imagesUrl  = 'data/images/console/';
	public $filesPath = 'files/console/files/';
	public $filesUrl  = 'data/files/console/';


	protected $table = 'console'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'title',
		'description',
		'url',
		'statusId',
		'categoryId',
		'date',
		'managerId',
		'viewDate',
		'archiveDate',
		'priority'
	);

	public function rules()
	{
		return array(
			'title' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'description' => array(
				'adapt' => '_adaptHtml',
			),
			'url' => array(
				'adapt' => '_adaptHtml',
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
			'viewDate' => array(
				'adapt' => '_adaptRegDate',
			),
			'priority' => array(
				'adapt' => '_adaptPriority',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date'     => array('_outDateTime'),
			'viewDate' => array('_outDate')
		);
	}

}
