<?php
namespace core\authorization;
class UserFactory extends \core\modules\base\ModuleAbstract
{
	use \core\traits\ObjectPool;

	static protected $instance = null;

	private $userId;
	private $userInfo;
	private $userLogin;
	private $userPassword;

	private static function init()
	{
		self::$instance = new UserFactory();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	function __construct()
	{
		parent::__construct();
	}

	public function getUserById($id)
	{
		try {
			return $this->setUserInfoById($id)
						->getUserObject();
		} catch (\Exception $e){
			$this->exceptionHandler($e);
		}
	}

	private function setUserInfoById($id)
	{
		$this->setUserId($id)->setUserInfo($this->loadUserInfoById());
		return $this;
	}

	private function setUserId($id)
	{
		$this->userId = (int)$id;
		$this->checkUserId();
		return $this;
	}

	private function setUserInfo($userInfo)
	{
		$this->userInfo = $userInfo;
		$this->checkUserInfo()->checkGroupAndThrowException();
		return $this;
	}

	private function checkUserId()
	{
		if (empty($this->userId))
			throw new \exceptions\ExceptionUserFactory('User ID is null!', 16);
		return $this;
	}

	private function loadUserInfoById()
	{
		return $this->getInfoById($this->userId);
	}

	private function exceptionHandler(\Exception $e, $postfix = '')
	{
		$handlerMethodName = 'handlerFactoryExceptionCode'.$e->getCode().$postfix;
		if (method_exists($this, $handlerMethodName))
			$this->$handlerMethodName($e);
		else
			throw $e;
	}

	private function checkUserInfo()
	{
		if (empty($this->userInfo))
			throw new \exceptions\ExceptionUserFactory('User with ID="'.$this->userId.'" was not found in table "'.$this->mainTable.'"!', 2);
		if (empty($this->userInfo['status']))
			throw new \exceptions\ExceptionUserFactory('User was blocked in table "'.$this->mainTable.'"!', 8);
		return $this;
	}

	private function checkGroupAndThrowException()
	{
		if ( !$this->checkGroup($this->userInfo['group']) )
			throw new \exceptions\ExceptionUserFactory('Group was not found with user ID='.$this->userId.'!', 4);
		return $this;
	}

	public function checkGroup($group)
	{
		return class_exists($this->getClassName($group), true);
	}

	private function getClassName($group)
	{
		return ucfirst($group);
	}

	private function getUserObject()
	{
		if (!$this->checkGroup($this->userInfo['group']))
			throw new \exceptions\ExceptionUserFactory('User with ID="'.$this->userId.'" was not found class "'.$userClass.'"!', 32);

		$userClass = $this->getClassName($this->userInfo['group']);

		if(in_array('interfaces\IUserForAuthorization', class_implements($userClass)))
			return $this->getObject($userClass, $this->userInfo['id']);
		else
			throw new \Exception('Object of class "'.$userClass.'" does not implement interface "/interfaces/IUserForAuthorization" in "'.get_class($this).'"!');
	}

	public function getUserByLogin($login, $password)
	{
		try {
			return $this->setLogin($login)
						->setPassword($password)
						->setUserInfo($this->loadUserInfoByLogin())
						->getUserObject();
		} catch (\exceptions\ExceptionUserFactory $e) {
			$this->exceptionHandler($e);
		}
	}

	private function handlerFactoryExceptionCode2()
	{
		$this->checkLoginExistsAndThrowException();
		throw new \exceptions\ExceptionLogin('User "'.$this->userLogin.'" was not found!', 4);
	}

	private function checkLoginExistsAndThrowException()
	{
		if ($this->loginExists($this->userLogin))
			throw new \exceptions\ExceptionLogin('Incorrect password for login "'.$this->userLogin.'"!', 8);
		return $this;
	}

	public function loginExists($login)
	{
		return $this->isFieldExist($login, 'login');
	}

	private function handlerFactoryExceptionCode8()
	{
		throw new \exceptions\ExceptionLogin('User "'.$this->userLogin.'" was blocked!', 16);
	}

	private function setLogin($login)
	{
		$this->userLogin = (string)$login;
		$this->checkLogin();
		return $this;
	}

	private function checkLogin()
	{
		if (empty($this->userLogin))
			throw new \exceptions\ExceptionLogin('Login value is empty!', 2);
		return $this;
	}

	private function setPassword($password)
	{
		$this->userPassword = (string)$password;
		return $this;
	}

	private function loadUserInfoByLogin()
	{
		$filter = array(
			'where' => array(
				'query' => '`login` = "?s" AND `password` = "?s"',
				'data'  => array($this->userLogin, $this->userPassword),
			)
		);
		return $this->getOne('*', $filter);
	}

	public function getUserInfoById($id)
	{
		return $this->setUserInfoById($id)->userInfo;
	}

	public function addUser($login, $password, $group, $status = 1)
	{
		if ($this->loginExists($login))
			throw new \exceptions\ExceptionUserFactory('Login "'.$login.'" is already exists!', 64);
		$data = array(
			'login'   =>$login,
			'password'=>md5($password),
			'group'   =>(string)$group,
			'status'  =>(int)$status
		);

		return ($this->baseAdd($data)) ? $this->lastInsertId() : false;
	}

	public function editPassword($newPassword, $userId)
	{
		$data = array(
			'password' => md5($newPassword),
			'id'       => (int)$userId,
		);
		return $this->baseEdit($data);
	}

	public function editPasswordRandom($param)
	{

	}

	public function editLogin($newLogin, $userId)
	{
		if ($this->loginExists($newLogin))
			throw new \exceptions\ExceptionUserFactory('Login "'.$newLogin.'" is already exists!', 64);
		$data = array(
			'login' => $newLogin,
			'id'    => $userId,
		);
		return $this->baseEdit($data);
	}

	public function editGroup($param)
	{

	}

	public function editStatus($param)
	{

	}

	public function addRecoveryToken($login)
	{
		if ($this->loginExists($login)){
			$token = $this->getRecoveryObject()->addToken($this->getUserIdByLogin($login));
			return $token;
		}
		throw new \exceptions\ExceptionLogin('User "'.$this->userLogin.'" was not found!', 4);
	}

	private function getRecoveryObject()
	{
		return new RecoveryPassword();
	}

	public function getUserIdByLogin($login)
	{
		$filter = array(
			'where' => array(
				'query' => '`login` = "?s"',
				'data'  => array($login),
				)
			);
		return array_shift($this->getOne('id', $filter));
	}

	public function editPasswordByToken($token, $newPassword)
	{
		$userId = $this->getRecoveryObject()->getUserIdByToken($token);
		if ($userId){
			if ($this->editPassword($newPassword, $userId))
				return $userId;
		}
		return false;
	}

	public function removeUser($userId)
	{
		return $this->deleteById($userId);
	}
	
	public function searchUser($searchWord, $domainAlias = null, $excludeStatuses = array())
	{
		$data = array();
		$query = 'SELECT `id` FROM `'.$this->mainTable().'` WHERE `login` LIKE \'%?s%\' ' ;

		$data = array_merge($data, array($searchWord, $domainAlias));

		$result = \core\db\Db::getMysql()->rowsAssoc($query, $data);
		$objects = array();
		foreach ($result as $value) {
			$objects[] = $this->getUserById($value['id']);
		}
		return $objects;
	}

}