<?php
namespace controllers\front;
class RecoveryFrontController extends \controllers\base\Controller
{
	private $login;
	private $captcha;
	private $newPassword;
	private $newPasswordConfirm;
	private $token;
	private $mailObject;
	private $userId;

	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __call($name, $arguments)
	{		
		$this->setAction($name)->defaultAction();
	}

	public function defaultAction()
	{		
		if (!$this->action) {
			$this->setAction('password')->password();
		} else
			$this->redirect404();
	}
	
	public function password()
	{
		$this->setContent('crumbs', 'Востановление пароля')
			->includeTemplate('account/passwordRecovery');
	}
	
	public function ajaxGetRecoveryToken()
	{		
		$this->ajaxResponse($this->getRecoveryToken());
	}
	
	public function getRecoveryToken()
	{
		return $this->setPostData()
//				->checkCaptcha()
				->checkLogin()
				->sendToken();
	}
	
	private function setPostData()
	{
		$this->login              = (isset($this->getPOST()['login']))              ? $this->getPOST()['login']              : null;
//		$this->captcha            = (isset($this->getPOST()['captcha']))            ? $this->getPOST()['captcha']            : null;
		$this->newPassword        = (isset($this->getPOST()['newPassword']))        ? $this->getPOST()['newPassword']        : null;
		$this->newPasswordConfirm = (isset($this->getPOST()['newPasswordConfirm'])) ? $this->getPOST()['newPasswordConfirm'] : null;
		$this->token              = (isset($this->getPOST()['token']))              ? $this->getPOST()['token']              : null;
		return $this;
	}
	
	private function checkCaptcha()
	{
		if ($this->getSESSION()['captcha']!=$this->captcha)
			$this->setError ('captcha', 'Fail code from control image');
		return $this;
	}
	
	private function checkLogin()
	{
		if (empty($this->login)) {
			$this->setError ('login', 'Login is empty');
			return $this;
		}
		if (!UserFactory::getInstance()->loginExists($this->login))
			$this->setError ('login', 'This login was not found');
		return $this;
	}
	
	private function sendToken()
	{
		$errors = $this->getError();
		if (empty($errors)) {
			$token = UserFactory::getInstance()->addRecoveryToken($this->login);
			$this->setToken($token)
				 ->sendTokenMail();
			return true;
		}
		return $errors;
	}
	
	private function setToken($token)
	{
		$this->token = $token;
		return $this;
	}
	
	public function ajaxEditPasswordByToken()
	{
		$this->ajaxResponse($this->editPasswordByToken());
	}
	
	public function editPasswordByToken()
	{
		$this->setPostData()->checkPasswords();
		if ($this->getError()) {
			return $this->getError();
		}
		if ($userId = UserFactory::getInstance()->editPasswordByToken($this->token, $this->newPassword)) {
			$this->setUserId($userId)->sendNewPasswordMail();
			return true;
		} else
			$this->setError ('token', 'Token does not exist or is removed');
		return $this->getError();
	}
	
	private function checkPasswords()
	{
		if (empty($this->newPassword)) {
			$this->setError ('newPassword', 'Password should not be empty');
			return $this;
		}
		if ($this->newPassword != $this->newPasswordConfirm) {
			$this->setError ('newPasswordConfirm', 'Passwords do not match');
		}
		return $this;
	}
	
	private function setUserId($userId)
	{
		$this->userId = $userId;
		return $this;
	}
	
	private function sendNewPasswordMail()
	{
		$this->getMailObject()->setPassword($this->newPassword)->sendNewPassword();
	}
	
	private function getMailObject()
	{
		return new MailClient($this->getUserObject());
	}
	
	private function getUserObject()
	{
		$userFactory = UserFactory::getInstance();
		if (empty ($this->userId))
			$this->setUserId ($userFactory->getUserIdByLogin($this->login));
		return UserFactory::getInstance()->getUserById($this->userId);
	}
	
	private function sendTokenMail()
	{
		$this->getMailObject()->setToken($this->token)->sendTokenRecoveryPassword();
	}
	
	
}