<?php
namespace controllers\base;
abstract class AuthorizationBaseController extends Controller
{
	protected static $authorizatedUser;

	private $login;
	private $password;
	private $cookieSetFlag;
	private $captcha;
	private $guestUserClass = '\core\authorization\Guest';
	private $user;

	protected $errorCode;
	protected $errorMessage;
	protected $link;
	protected $ban;

	protected $postLoginKey;
	protected $postPasswordKey;
	protected $postCookieKey;
	protected $postCaptchaKey;
	protected $postSubmitKey;
	protected $requestLogoutKey;

	protected $postLogin;
	protected $postPassword;
	protected $postCookie;
	protected $postCaptcha;

	protected $sessionArrayKey;
	protected $cookieArrayKey;

	function __construct()
	{
		$this->checkCookieArrayKey()
			 ->checkSessionArray()
			 ->getCookieData()
			 ->getSessionData()
			 ->setLink();
		parent::__construct();
	}

	private function checkSessionArray()
	{
		if (empty($this->sessionArrayKey))
			$this->setCriticalError('Is not set array key for session in '.get_class($this).'!');
		return $this;
	}

	private function setCriticalError($message,$code = null)
	{
		throw new \Exception($message,$code);
	}

	private function checkCookieArrayKey()
	{
		if (empty($this->cookieArrayKey))
			$this->setCriticalError('Is not set array key for cookie in '.get_class($this).'!');
		return $this;
	}

	private function getCookieData()
	{
		return $this->getCookieLogin()->getCookiePassword();
	}

	private function getCookieLogin()
	{
		if (!empty($_COOKIE[$this->cookieArrayKey]['login']))
			$this->setLogin($_COOKIE[$this->cookieArrayKey]['login']);
		return $this;
	}

	public function setLogin($login)
	{
		$this->login = $login;
		return $this;
	}

	private function getCookiePassword()
	{
		if (!empty($_COOKIE[$this->cookieArrayKey]['password']))
			$this->setPassword($_COOKIE[$this->cookieArrayKey]['password']);
		return $this;
	}

	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	private function getSessionData()
	{
		return $this->getSessionLogin()->getSessionPassword();
	}

	private function getSessionLogin()
	{
		if (!empty($this->getSESSION()[$this->sessionArrayKey]['login']))
			$this->setLogin($this->getSESSION()[$this->sessionArrayKey]['login']);
		return $this;
	}

	private function getSessionPassword()
	{
		if (!empty($this->getSESSION()[$this->sessionArrayKey]['password']))
			$this->setPassword($this->getSESSION()[$this->sessionArrayKey]['password']);
		return $this;
	}

	public function setCookie($cookie)
	{
		$this->cookieSetFlag = $cookie;
		return $this;
	}

	protected function setCaptcha($captcha)
	{
		$this->captcha = $captcha;
		return $this;
	}

	public function login()
	{
		
		if ($this->login) {
			try {
				$authConfig = \core\Configurator::getInstance()->getArrayByKey('authorization');
				$authorization = new \core\authorization\Authorization($authConfig);
				$user = $authorization->login($this->login, $this->password, $this->captcha);
				$this->setAuthorizatedUser($user)->setSessionData()->setCookieData();
				return true;
			} catch (\exceptions\ExceptionLogin $e){
				$this->setErrorDataFromException($e);
			}
		}
		$this->checkSubmitKey()->setAuthorizatedGuest();
		return false;
	}

	protected function setAuthorizatedUser($userObject)
	{
		if (!is_object($userObject))
		    throw new \Exception('Authorized user is not an object!');
		if ( $userObject->statusId == 2 )
		    throw new \Exception('Authorized user is blocked!');
		AuthorizationBaseController::$authorizatedUser = $userObject;
		return $this;
	}

	protected function setSessionData()
	{
		$_SESSION[$this->sessionArrayKey]['login']    = $this->login;
		$_SESSION[$this->sessionArrayKey]['password'] = $this->password;
		$this->updateSESSION();
		return $this;
	}

	protected function setCookieData()
	{
		if ($this->cookieSetFlag){
			setcookie($this->cookieArrayKey.'[login]'	,$this->login	, time()+2592000, '/');
			setcookie($this->cookieArrayKey.'[password]',$this->password, time()+2592000, '/');
		}
		return $this;
	}

	private function setErrorDataFromException($exception)
	{
		$this->setErrorData($exception->getMessage(), $exception->getCode());
		return $this;
	}

	private function setErrorData($message=null, $code=null)
	{
		$this->errorCode    = $code;
		$this->errorMessage = $message;
		return $this;
	}

	private function checkSubmitKey()
	{
		if (isset($this->getPOST()[$this->postSubmitKey]) && empty($this->errorMessage))
			$this->setErrorData ('Not specified authorization data!', 512);
		return $this;
	}

	protected function setAuthorizatedGuest()
	{
		$guestUser = new $this->guestUserClass;
		return $this->setAuthorizatedUser($guestUser);
	}

	protected function setLink()
	{
		$parts = explode('&',$this->getSERVER()['QUERY_STRING']);
		$link = $this->getSERVER()['REQUEST_URI'].'?';
		foreach ($parts as $part) {
			if (!empty($part)) $link .= $part.'&';
		}
		$link = substr($link,0,-1);
		$this->link = $link;
		return $this;
	}

	public function logout()
	{
		return $this->setLogin(null)
					->setPassword(null)
					->setCookie(true)
					->setCookieData()
					->setSessionData()
					->setAuthorizatedGuest();
	}

	public function authorization()
	{
		if (isset($this->getPOST()[$this->postSubmitKey]))
			$this->setRequestData();
		return $this->checkLogout()->login();
	}

	protected function setRequestData()
	{
		return $this->getPostData()
					->setLogin($this->postLogin)
					->setPassword(strtolower(md5($this->postPassword)))
					->setCookie($this->postCookie)
					->setCaptcha($this->postCaptcha);
	}

	protected function getPostData()
	{
		$this->postLogin    = (isset($this->getPOST()[$this->postLoginKey]))    ? $this->getPOST()[$this->postLoginKey]    : null;
		$this->postPassword = (isset($this->getPOST()[$this->postPasswordKey])) ? $this->getPOST()[$this->postPasswordKey] : null;
		$this->postCookie   = (isset($this->getPOST()[$this->postCookieKey]))   ? $this->getPOST()[$this->postCookieKey]   : null;
		$this->postCaptcha  = (isset($this->getPOST()[$this->postCaptchaKey]))  ? $this->getPOST()[$this->postCaptchaKey]  : null;
		return $this;
	}

	protected function checkLogout()
	{
		if (!empty($this->getPOST()[$this->requestLogoutKey]) || !empty($this->getGET()[$this->requestLogoutKey]))
			$this->logout();
		return $this;
	}

	protected function getErrorArray()
	{
		return array('errorCode'=>$this->errorCode, 'errorMessage'=>$this->errorMessage);
	}

	public function isGuest()
	{
		return ($this->authorizatedUser() instanceof $this->guestUserClass);
	}

	public function authorizatedUser()
	{
		return AuthorizationBaseController::$authorizatedUser;
	}

	public function isAuthorizated()
	{
		return !$this->isGuest();
	}

	public function authorizeUser($user, $cookie = false)
	{
		return $this->setUser($user)
					->setDataFromUser()
					->setCookie((bool)$cookie)
					->checkLogout()
					->login();
	}

	protected function setUser($user)
	{
		$this->user = $user;
		return $this->checkUser();
	}

	protected function checkUser()
	{
		if ($this->user instanceof \interfaces\IUserForAuthorization)
			return $this;
		throw new \Exception('Passed user object is not a class implements \interfaces\IUserForAuthorization Interface in '.get_class($this).'!');
	}

	protected function setDataFromUser()
	{
		return $this->setLogin($this->user->getLogin())
					->setPassword($this->user->getPassword());
	}

	public function isUserAuthorizedInCookie()
	{
		return isset($this->getCOOKIE()[$this->cookieArrayKey]['password']);
	}
}