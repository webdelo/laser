<?php
namespace core\authorization;
abstract class AuthorizatedUser extends \core\modules\base\ModuleDecorator implements \interfaces\IUserForAuthorization
{
	public function getLogin()
	{
		return $this->getParentObject()->getLogin();
	}

	public function getGroup()
	{
		return $this->getParentObject()->getGroup();
	}

	public function getStatus()
	{
		return $this->getParentObject()->getStatus();
	}

	public function getPassword()
	{
		return $this->getParentObject()->getPassword();
	}

	public function editLogin($newLogin)
	{
		return $this->getParentObject()->editLogin($newLogin);
	}

	public function checkPassword($password)
	{
		return $this->getParentObject()->checkPassword($password);
	}

	public function editPassword($newPassword, $newPasswordConfirm)
	{
		return $this->getParentObject()->editPassword($newPassword, $newPasswordConfirm);
	}

	public function getUserData()
	{
		return $this->getParentObject()->getUserData();
	}

	public function deleteUser()
	{
		return $this->getParentObject()->deleteUser();
	}

	public function delete()
	{
		return $this->getParentObject()->delete();
	}

	public function getUserName ()
	{
		return $this->getParentObject()->getUserName();
	}
}