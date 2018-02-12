<?php
namespace core\authorization;
class UserDecorator extends \core\modules\base\ModuleDecorator
{
	public $userId;
	public $user;

	function __construct($object, $userId)
	{
		parent::__construct($object);
		$this->userId = $userId;
	}

	function getUser()
	{
		if(empty($this->user)){
			$this->user = UserFactory::getInstance()->getUserById($this->userId);
		}
		return $this->user;
	}
}