<?php
namespace core;
class RequestHandler
{
	use \core\traits\RequestHandler;
	
	private $post;
	private $get;
	private $session;
	private $server;
	private $cookie;
	
	private $domain;
	private $domainAlias;
	private $lang;
	private $part;
	private $mainController;

	static private $instance = null;

	private static function init()
	{
		self::$instance = new RequestHandler();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	private function __construct() {}
	
	public function getPOST()
	{
		if (empty($this->post))
			$this->post = new ArrayWrapper($_POST);
		return $this->post;
	}
	
	public function getGET()
	{
		if (empty($this->get))
			$this->get = new ArrayWrapper($_GET);
		return $this->get;
	}
	
	public function getSESSION()
	{
		if (empty($this->session))
			$this->session = new ArrayWrapper($_SESSION);
		return $this->session;
	}
	
	public function getSERVER()
	{
		if (empty($this->server))
			$this->server = new ArrayWrapper($_SERVER);
		return $this->server;
	}
	
	public function getCOOKIE()
	{
		if (empty($this->cookie))
			$this->cookie = new ArrayWrapper($_COOKIE);
		return $this->cookie;
	}
	
	public function getREQUEST()
	{
		if (empty($this->request))
			$this->setREQUEST($_REQUEST);
		return $this->request;
	}
	
	public function setREQUEST($request)
	{
		if (empty($this->post))
			$this->request = new ArrayWrapper($request);
		return $this->post;
	}
	
	public function getCurrentDomain()
	{
		if (empty($this->domain)){
			$this->domain = \core\url\UrlDecoder::getInstance()->getDomain();
		}
		return $this->domain;
	}
	
	public function getCurrentDomainAlias()
	{
		if (empty($this->domainAlias)) {
			$this->domainAlias = \core\url\UrlDecoder::getInstance()->getDomainAlias();
		}
		return $this->domainAlias;
	}
	
	public function getCurrentLang()
	{
		if (i18n\LangHandler::getInstance()->getLang() && empty($this->lang)) {
			$this->lang = i18n\LangHandler::getInstance()->getLang();
		}
		return $this->lang;
	}
	
	public function getCurrentPart()
	{
		if (\core\url\UrlDecoder::getInstance()->part && empty($this->part)) {
			$this->part = \core\url\UrlDecoder::getInstance()->part;
		}
		return $this->part;
	}
	
	public function getMainController()
	{
		if (empty($this->mainController)) {
			$controller = \controllers\base\ControllerFactory::getInstance()->getMainController();
			$this->setController($controller);
		}
		return $this->mainController;
	}
	
	private function setController($controller)
	{
		if (is_object($controller))
			$this->mainController = get_class($controller);
		elseif (is_string($controller))
			$this->mainController = $controller;
		else
			throw new \Exception('Incorect variable value for Controller::setController() in class '.get_class($this).'!');
		return $this;
	}
	
	public function updateGET()
	{
		$this->get = $_GET;
	}
	
	public function updatePOST()
	{
		$this->post = $_POST;
	}
	
	public function updateREQUEST()
	{
		$this->request = new ArrayWrapper($_REQUEST);
	}
	
	public function updateSERVER()
	{
		$this->server = $_SERVER;
	}
	
	public function updateSESSION()
	{
		$this->sessiont = $_SESSION;
	}
	
	public function updateCOOKIE()
	{
		$this->cookie = $_COOKIE;
	}
	
	public function isPart($part)
	{
		$partsList = \core\Configurator::getInstance()->url->getArrayByKey('parts');
		return in_array($part, $partsList);
	}

	public function isDefaultPart($part)
	{
		return (string)$part == \core\Configurator::getInstance()->url->default->part;
	}

	public function isDefaultLang($lang)
	{
		return (string)$lang == \core\Configurator::getInstance()->url->default->lang;
	}

	public function isDefaultController($controller)
	{
		$defaultControllerConfigKey = 'default'.ucfirst(TYPE).'Controller';
		return ((string)$controller == \core\Configurator::getInstance()->controllers->$defaultControllerConfigKey);
	}

	public function isCurrentDomainAlias($domainAlias)
	{
		return ($this->domainAlias == $domainAlias);
	}

	public function isCurrentDomain($domain)
	{
		return ($this->domain == $domain);
	}
	
	public function isMainController($controllerName)
	{
		return (ControllerFactory::getInstance()->getMainController() == ucfirst($controllerName));
	}
	
	public function isIndex()
	{
		return \core\url\UrlDecoder::getInstance()->getCurrenPageWithoutQueryString() == '/';
	}
	
	public function isNotIndex()
	{
		return !$this->isIndex();
	}
	
	public function isDeveloperDomain()
	{
		return url\UrlDecoder::getInstance()->isDeveloperDomain();
	}
	
	public function isProductionDomain()
	{
		return url\UrlDecoder::getInstance()->isProductionDomain();
	}
	
	public function getDevelopersDomainAlias($domainAlias = null)
	{
		$domainAlias = empty($domainAlias) ? $this->getCurrentDomainAlias() : $domainAlias;
		return \core\Configurator::getInstance()->url->domainsDevelopersAliases->$domainAlias;
	}
	
	public function moveRequestLevel()
	{
		return \core\url\UrlDecoder::getInstance()->moveRequestLevel();
	}
}