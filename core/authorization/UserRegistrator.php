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
				$this->setError('login');
			else
				$this->newUserLogin = $login;
		} else
			$this->setError('login');
		return $this;
	}

	public function setPassword($password, $passwordConfirmation)
	{
		if(empty($password))
			$this->setError('password');
		if ($password !== $passwordConfirmation)
			$this->setError('passwordConfirm');
		else
			$this->newUserPassword = $password;
		return $this;
	}

	protected function setGroup($group)
	{
		$group = (string)$group;
		if (!UserFactory::getInstance()->checkGroup($group))
			$this->setError('group_id');
		else
			$this->newUserGroup = $group;
		return $this;
	}

	public function add($data, $fields = NULL)
	{
		$this->setGroup($this->getConfig()->getObjectClass());
		$fields = is_array($fields) ? $fields : $this->_moduleConfig->getObjectFields();
		$this->_beforeChangeWithoutAdapt($data, $fields);
		if ($this->getErrors()) {
			return false;
		} else {
			$data['id'] = $this->addLogin();
			$fields = $this->clearFields($fields);
			$res = parent::add($data, $fields);
			if (!$res) {
				throw new \Exception ('Warning: ' . serialize($this->getErrors()));
			}
			return $data['id'];
		}
	}

	private function clearFields($fields)
	{
		if (isset($fields['login']))
			unset($fields['login']);
		if (isset($fields['password']))
			unset($fields['password']);
		return $this;
	}
	
	public function filterByLogin($login)
	{
		$this->setSubquery(' AND `id` IN ( SELECT `id` FROM `tbl_user_logins` WHERE `login` = \'?s\' ) ', (string)$login);
		
		return $this;
	}
}