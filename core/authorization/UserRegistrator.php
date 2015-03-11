<?php
namespace core\authorization;
class UserRegistrator extends \core\modules\base\ModuleObjects
{
	protected $newUserLogin;
	protected $newUserPassword;
	protected $newUserGroup;
	protected $newUserStatus;

	protected function addLogin()
	{
		$this->checkUserStatus();
		if ($this->getErrors())
			return false;
		return UserFactory::getInstance()->addUser($this->newUserLogin, $this->newUserPassword, $this->newUserGroup, $this->newUserStatus);
	}

	private function checkUserStatus()
	{
		if (!isset($this->newUserStatus))
			$this->newUserStatus = 1;
		return $this;
	}

	public function setLogin($login)
	{
		$login = (string)$login;
		if ($this->_moduleConfig->validLogin($login)) {
			if (UserFactory::getInstance()->loginExists($login))
				$this->setError('login', 'Логин  "'.$login.'" уже существует!');
			else
				$this->newUserLogin = $login;
		} else
			$this->setError('login', 'Логин не является E-mail');
		return $this;
	}

	public function setPassword($password, $passwordConfirmation)
	{
		if(empty($password))
			$this->setError('password', 'Укажите пароль!');
		if ($password !== $passwordConfirmation)
			$this->setError('passwordConfirm', 'Пароли не совпадают!');
		else
			$this->newUserPassword = $password;
		return $this;
	}

	protected function setGroup($group)
	{
		$group = (string)$group;
		if (!UserFactory::getInstance()->checkGroup($group))
			$this->setError('group_id', 'Несуществующая группа пользователей!');
		else
			$this->newUserGroup = $group;
		return $this;
	}

	public function add($data, $fields = NULL)
	{
		$this->setGroup($this->getConfig()->getObjectClass());
		$this->_beforeChangeWithoutAdapt($data, $this->_moduleConfig->getObjectFields());
		if ($this->getErrors()) {
			return false;
		} else {
			$data['id'] = $this->addLogin();
			parent::add($data);
			return $data['id'];
		}
	}
}