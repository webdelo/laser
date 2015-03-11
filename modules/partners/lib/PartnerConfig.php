<?php
namespace modules\partners\lib;
class PartnerConfig extends \core\modules\base\ModuleConfig
{
	const STATUS_ACTIVE = 1;
	const REMOVED_STATUS_ID = 3;

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass = '\modules\partners\lib\Partner';
	protected $objectsClass = '\modules\partners\lib\Partners';

	protected $table = 'partners'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'name',
		'description',
		'statusId',
		'priority',
		'moduleId',
		'cashRate'
	);

	public $templates = 'modules/partners/tpl/';

	public function rules()
	{
		return array(
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'name,description' => array(
				'adapt' => '_adaptHtml',
			),
			'ca	shRate' => array(
				'validation' => array('_validInt', array('notEmpty'=>false)),
			),
		);
	}

}