<?php
namespace core\authorization;
abstract class AuthorizatedUserObject extends \core\modules\base\ModuleObject
{
	private $loginInfo = array();
	private $userFactoryObject;

	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, $configObject);
		$this->userFactoryObject = UserFactory::getInstance();
		$this->loginInfo         = $this->userFactoryObject->getUserInfoById($this->objectId);
	}

	public function getLogin()
	{
		return $this->loginInfo['login'];
	}

	public function getGroup()
	{
		return $this->loginInfo['group'];
	}

	public function getStatus()
	{
		return $this->loginInfo['status'];
	}

	public function getPassword()
	{
		return $this->loginInfo['password'];
	}

	public function editLogin($newLogin)
	{
		$this->checkNewLogin($newLogin);

		if ($this->getErrors())
			return false;

		$result = $this->userFactoryObject->editLogin($newLogin, $this->objectId);
		if ($result)
			$this->loginInfo['login'] = $newLogin;
		return $result;
	}

	private function checkNewLogin($login)
	{
		if ($this->_moduleConfig->validLogin($login)){
			if ($this->userFactoryObject->loginExists($login))
				$this->setError('login', 'This login is already exist');
		}
		else
			$this->setError('login', 'This login is not valid E-mail');
		return $this;
	}

	public function checkPassword($password)
	{
		return ( md5(strtolower($password)) == $this->loginInfo['password'] );
	}

	public function editPassword( $newPassword, $newPasswordConfirm )
	{
		$newPassword        = (strtolower($newPassword));
		$newPasswordConfirm = (strtolower($newPasswordConfirm));
		if( empty($newPassword) ){
			$this->setError('password', 'Введите пароль');
			return false;
		}

		if ( $newPassword !== $newPasswordConfirm ) {
			$this->setError('passwordConfirm', 'Пароли не совпадают');
			return false;
		}

		$result = $this->userFactoryObject->editPassword($newPassword, $this->objectId);
		if ($result)
			$this->loginInfo['password'] = $newPassword;
		return $result;
	}

	public function getUserData()
	{
		return $this->getObjectInfo();
	}

	public function deleteUser()
	{
		return $this->userFactoryObject->removeUser($this->objectId);
	}

	public function delete()
	{
		$removedStatus = $this->getRemovedStatus();
		if($removedStatus)
			return \core\modules\base\ModuleObject::delete();
		else
			if ($this->deleteUser())
				return \core\modules\base\ModuleObject::delete();
		return false;
	}

	public function getUserName ()
	{
		$userName = $this->getLogin();
		if ($this->firstname && $this->lastname)
			$userName = $this->firstname.' '.$this->lastname;
		elseif ($this->name)
			$userName = $this->name;

		return $userName;
	}
}