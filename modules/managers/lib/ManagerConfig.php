<?php
namespace modules\managers\lib;
class ManagerConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Email;

	protected $objectClass = '\modules\managers\lib\Manager';
	protected $objectsClass = '\modules\managers\lib\Managers';

	protected $table = 'managers'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'name',
		'description',
		'statusId',
		'partnerId',
	);

	public $templates = 'modules/managers/tpl/';

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'statusId,partnerId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'description' => array(
				'adapt' => '_adaptHtml',
			),
		);
	}

	public function validLogin ($login)
	{
		return ( $this->_validEmail($login, array('notEmpty'=>true, 'key'=>'login')) == 'true' );
	}
}
