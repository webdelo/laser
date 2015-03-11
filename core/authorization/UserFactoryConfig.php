<?php
namespace core\authorization;
class UserFactoryConfig extends \core\modules\base\ModuleConfig
{
	protected $table = 'user_logins'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'login',
		'password',
		'group',
		'status'
	);
	
	public function rules()
	{
		return array(
			'password' => array(
				'adapt' => '_adaptPassword',
			)
		);
	}
}