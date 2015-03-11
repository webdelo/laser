<?php
namespace modules\administrators\lib;
class AdministratorConfig extends \core\modules\base\ModuleConfig
{
	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;

	const MANAGERS_CATEGORY_ID = 26;

	protected $objectClass = '\modules\administrators\lib\Administrator';
	protected $objectsClass = '\modules\administrators\lib\Administrators';

	protected $table = 'administrators'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'name',
		'firstname',
		'lastname',
		'date',
		'groupId',
		'statusId',
		'address',
		'mobile',
		'phone',
		'note',
		'email',
		'skype',
	);

	public $templates = 'modules/administrators/tpl/';

	public function rules()
	{
		return array(
			'statusId,groupId' => array(
				'validation' => array('_validInt'),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'firstname,lastname,address,mobile,phone,note' => array(
				'adapt' => '_adaptHtml',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

	public function validLogin ($login) {
		return !empty($login);
	}

	public function _adaptRegDate($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : time() ;
	}

	public function _outDate($data)
	{
		return \core\utils\Dates::convertDate($data, 'simple');
	}
}
