<?php
namespace core\authorization;
class Authorization extends \core\Model
{
	use \core\traits\RequestHandler;

	private $timeout;
	private $captchaFlag;
	private $tryInfo = array();
	private $capthcaFlag;
	private $ip;
	private $statusBlocked = 2;

	private $countTriesBeforeCaptcha = 3;
	private $countTriesBeforeBan     = 6;
	private $defaultTimeoutSeconds   = 600;

	private $login;
	private $password;
	private $captcha;

	public function __construct($config)
	{
		parent::__construct();
		$this->setConfig($config);
	}

	private function setConfig($config)
	{
		$this->timeout = ($config['timeout']) ? $config['timeout'] : $this->defaultTimeoutSeconds;
		return $this;
	}

	public function mainTable()
	{
		return TABLE_PREFIX.'authorization';
	}

	public function login($login, $password, $captcha = null)
	{
		return $this->setLogin($login)
					->setPassword($password)
					->setCaptcha($captcha)
					->setIp()
					->getTryInfo()
					->checkTry()
					->getUser();
	}

	private function setLogin($login)
	{
		$this->login = (string)$login;
		return $this;
	}

	private function setPassword($password)
	{
		$this->password = (string)$password;
		return $this;
	}

	private function setCaptcha($captcha)
	{
		$this->captcha = (string)$captcha;
		return $this;
	}

	private function setIp()
	{
		$this->ip = $this->getSERVER()['REMOTE_ADDR'];
		return $this;
	}

	private function getTryInfo()
	{
		$this->tryInfo = $this->getInfoFromDbByIP();
		if (empty($this->tryInfo)) {
			$this->addFirstTry();
		}
		return $this;
	}

	private function getInfoFromDbByIP()
	{
		$filter = array(
			'where' => array(
				'query' => '`ip` = "?s"',
				'data' => array($this->ip),
				)
			);
		return $this->getOne('*', $filter);
	}

	private function addFirstTry()
	{
		$this->cleanTries();
		$tryInfo = array('ip'=>$this->ip, 'date'=>time(), 'try'=>1, 'ban'=>0);
		$this->baseAdd($tryInfo, array('ip','date','try'));
		$this->tryInfo = $tryInfo;
		$this->tryInfo['id'] = $this->lastInsertId();
		return $this;
	}

	private function cleanTries()
	{
		$query = '
			DELETE FROM
				`'.$this->mainTable().'`
			WHERE
				(`date`) < ?d
			';
		$data = array(time() - $this->timeout);
		\core\db\Db::getMysql()->query($query, $data);
		return $this;
	}

	private function checkTry()
	{
		return $this->checkBanAndThrowException()->checkCaptchaAndThrowException();
	}

	private function checkBanAndThrowException()
	{
		if (!empty($this->tryInfo['ban']))
			$this->returnError('Ban. You can make other try in 5 minutes!', 64);
		return $this;
	}

	private function returnError($message, $code)
	{
		throw new \exceptions\ExceptionLogin($message, $code);
	}

	private function checkCaptchaAndThrowException()
	{
		if (!$this->checkCaptchaAndAddTry())
			$this->returnError('Wrong text from the picture!', 32);
		return $this;
	}

	private function checkCaptchaAndAddTry()
	{
		if ($this->tryInfo['try'] > $this->countTriesBeforeCaptcha && $this->captcha != $this->getSESSION()['captcha']) {
			$try = $this->addTry();
			return false;
		}
		return true;
	}

	private function addTry()
	{
		$this->tryInfo['date'] = time();
		$this->tryInfo['try']++;
		$this->tryInfo['ban'] = ($this->tryInfo['try'] > $this->countTriesBeforeBan) ? 1 : 0;
		$this->edit($this->tryInfo, array('id','date','try', 'ban'));
		return $this;
	}

	private function getUser()
	{
		$user = UserFactory::getInstance()->getUserByLogin($this->login, $this->password);
		if ( $this->userIsBlocked($user) )
			$this->returnError('User is blocked', 32);
		$this->deleteTry();
		return $user;
	}

	private function userIsBlocked($userObject)
	{
	    return ($userObject->statusId == $this->statusBlocked);
	}

	private function deleteTry()
	{
		$this->baseDelete($this->tryInfo,array('id'));
	}

}
