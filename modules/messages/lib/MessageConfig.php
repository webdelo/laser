<?php
namespace modules\messages\lib;
class MessageConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\outAdapters\OutBase,
		\core\traits\outAdapters\OutDate;

	protected $objectClass = '\modules\messages\lib\Message';
	protected $objectsClass = '\modules\messages\lib\Messages';

	protected $tablePostfix = '_messages'; // set value without preffix!
	protected $idField = 'id';

	public $newStatus     = 1;
	public $activeStatus  = 2;
	public $moderStatus   = 3;
	public $blockedStatus = 4;
	public $removedStatus = 5;


	protected $objectFields = array(
		'id',
		'clientId',
		'ownerId',
		'date',
		'text',
		'statusId',
		'isSystem',
		'confirmedBy',
		'confirmedDate',
		'description'
	);

	public function rules()
	{
		return array(
			'text' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'clientId, ownerId, statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date, confirmedDate' => array(
				'adapt' => '_adaptRegDate',
			),
			'description' => array(
				'adapt' => '_adaptHtml'
			)
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDateTime'),
			'text' => array('_outHtml')
		);
	}

	public function getServiceObjectClass()
	{
		return '\modules\messages\lib\MessageService';
	}
}
