<?php
namespace core\url;
class UrlDecoder
{
	use \core\traits\RequestHandler;
	
	public  $hosturl;
	public  $currentUrl;
	
	static protected $object = null;
	
	private $url;
	private $request;
	private $config;
	private $default;
	private $parts;
	private $langs;
	private $langsDomains;
	private $developerDomains;
	private $domains;
	private $extensions = array();
	private $defaultConfig = array(
		'settings'=>
			array(
				'lang' => 'None', // 'SubDomain', 'Element', 'None'
				'part' => 'None', // 'SubDomain', 'Element', 'None'
				'controller' => 'Element', // 'Element', 'None'
			),
		'default'=>
			array(
				'lang'=>'en', 
				'part'=>'', 
			),
		'extensions'=>
			array(
				'.html', 
				'.html', 
				'.php', 
				'.asp', 
				'.aspx', 
				'.xml'
			),
		'parts'   => array(),
		'langs'   => array(),
		'domains' => array(),
	);
	
	static public function getInstance ()
	{
		if (is_null(self::$object))
			self::$object = new UrlDecoder();
		return self::$object;
	}

	function getObject()
	{
		return self::$object;
 	}
	
	private function __construct($config=null) {	}

	function setConfig($config) {
		if (!$config  || !is_array($config))
			$config = $this->setDefaultConfig();		
		
		$this->config       = $config['settings'];
		$this->default      = $config['default'];
		$this->langsDomains = $config['langs'];
		$this->langs        = array_keys($config['langs']);
		$this->parts        = $config['parts'];
		$this->extensions   = $config['extensions'];
		$this->domains      = $config['domains'];
		$this->developerDomains = $config['developerDomains'];
		
		return self::$object;
	}
	
	private function setDefaultConfig()
	{
		return $this->defaultConfig;
	}
	
	public function requestRebuildForAdmin ()
	{
		$this->setRequestData();
		$_REQUEST = array_merge($_REQUEST, $this->request, $this->url);
		return $this->setRequestToHandler($_REQUEST);
	}
	
	private function setRequestToHandler($request)
	{
		\core\RequestHandler::getInstance()->setREQUEST($request);
		return $this;
	}
	
	private function updateRequestInHandler()
	{
		\core\RequestHandler::getInstance()->updateREQUEST();
		return $this;
	}

	public function requestRebuild ()
	{
        $this->reset();
		$this->setRequestData();
		$_REQUEST = array_merge($_REQUEST, $this->request, $this->url);
		return $this->setRequestToHandler($_REQUEST);
	}
	
	public function requestRebuildWithoutController () 
	{
		$this->reset();
		$this->config['controller']='None';
		$this->setRequestData();
		$_REQUEST = array_merge($_REQUEST, $this->request, $this->url);
		return $this->setRequestToHandler($_REQUEST);
	}
	
	public function moveRequestLevel () 
	{
		$this->reset();
		$this->config['controller']='NextElement';
		$this->setRequestData();
		$_REQUEST = array_merge($this->request, $this->url);
		return $this->updateRequestInHandler();
	}

	public function reset () 
	{
		$this->url = null;
		$this->hosturl = null;
		$this->request = null;
        $this->getREQUEST()['controller'] = $this->getREQUEST()['action'] = $this->getREQUEST()['lang'] = '';
		$_REQUEST['controller'] = $_REQUEST['action'] = $_REQUEST['lang'] = '';
	}
        
    function setRequestData()
	{
		$this->setCurrentUrl();
		$this->setRequestFromQueryString();
		$this->setUrl();
		$this->setHostUrl();
                
		$this->parseUrl();
		$this->parseHostUrl();

		$methods = array(
			'setLang'.$this->config['lang'],
			'setPart'.$this->config['part'],
			'setController'.$this->config['controller'],
		);

		foreach($methods as $key=>$method)
			$this->$method();

		$this->setAction();
		return self::$object;
	}
	
	private function setRequestFromQueryString () 
	{
		foreach ($this->getREQUEST() as $key=>$value)
			$this->request[$key]= $value;
	}
	
	private function setUrl () 
	{
		if (empty($this->url)) {
			$this->url = $this->cleanRequestUri() ;
		}
	}

	private function cleanRequestUri ()
	{
		return str_replace('?'.$this->getSERVER()['QUERY_STRING'],'',$this->getSERVER()['REQUEST_URI']);
	}

	private function setHostUrl () 
	{
		if (empty($this->hosturl))
			$this->hosturl = str_replace('www.', '', $this->getSERVER()['HTTP_HOST']);
	}

	private function parseUrl () 
	{
		$this->setUrlByType();
		$this->url = substr($this->url, 1);
		$this->url = explode('/', $this->url);
		$this->url = array_map("urldecode", $this->url);
		$this->url = array_map("trim", $this->url);
		$this->removeEmptyElement();
	}

	private function removeExtensions ()
	{
		$this->url = str_replace($this->extensions, '', $this->url);
	}
	
	private function setUrlByType()
	{
		if(strstr($this->url, strtolower(TYPE)))
			$this->url = str_replace('/'.strtolower(TYPE).'/', '/', $this->url);
	}

	private function removeEmptyElement ()
	{
		if ($this->url[sizeof($this->url)-1] === '')
			array_pop($this->url);
	}

	private function parseHostUrl () 
	{
		$this->hosturl = explode('.', $this->hosturl);
		$this->hosturl = array_map("urldecode", $this->hosturl);
		$this->hosturl = array_map("trim", $this->hosturl);
	}

	public function setControllerNone ()
	{
		$this->request['controller'] = '';
	}

	public function setControllerElement ()
	{
		$this->request['controller'] = $this->shiftUrl();
	}
	
	public function setControllerNextElement ()
	{
		$this->request['lastController'] = $this->shiftUrl();
		$this->request['controller'] = $this->shiftUrl();
	}

	public function setControllerSubDomain ()
	{
		$this->request['controller'] = $this->shiftHostUrl();
	}

	public function setLangNone ()
	{
		$this->request['lang'] = $this->default['lang'];
	}

	public function setLangElement ()
	{
		 if (in_array($this->url[0], $this->langs))
			$this->request['lang'] = $this->shiftUrl();
		 else
			$this->setLangNone();
	}

	public function setLangSubDomain ()
	{
		 if (in_array($this->hosturl[0], $this->langs)) 
			$this->request['lang'] = $this->shiftHostUrl();
		 else
			$this->setLangNone();
	}

	public function setLangByDomain ()
	{
		$this->setLangNone();
		foreach ($this->langsDomains as $lang => $domaings) {
			if (is_array($domaings) ) {
				if ( in_array(implode('.', $this->hosturl), $domaings) ) {
					$this->request['lang'] = $lang;
					break;
				}
			}
		}
	}

	public function setPartNone ()
	{
		$this->request['part'] = $this->default['part'];
	}

	public function setPartElement ()
	{
		if (in_array($this->url[0], $this->parts))
			$this->request['part'] = $this->shiftUrl();
		 else
			$this->setPartNone();
		
	}

	public function setPartSubDomain ()
	{
		 if (in_array($this->hosturl[0], $this->parts))
			$this->request['part'] = $this->shiftHostUrl();
		 else
			$this->setPartNone();
	}

	public function setAction ()
	{
		if (isset($this->getREQUEST()['action']))
			if (!empty($this->getREQUEST()['action']))
				$this->request['action'] = $this->getREQUEST()['action'];
			else 
				$this->request['action'] = $this->shiftUrl();
	}
	// 
	private function shiftUrl(){
		return ($this->url) ? array_shift($this->url) : '' ;
	}
	private function shiftHostUrl(){
		return ($this->hosturl) ? array_shift($this->hosturl) : '' ;
	}
	
	public function getExtensions ()
	{
		return $this->extensions;
	}

	public function __get($key) 
	{
		if (isset($this->request[$key]))
			return $this->request[$key];
	}
	
	public function setCurrentUrl()
	{
		$protocol = 'http';
		$host = $this->getSERVER()['HTTP_HOST'];
		$uri = $this->getSERVER()['REQUEST_URI'];
		$this->currentUrl = $protocol.'://'.$host.$uri;
	}
	
	public function getDomain () {
		if (!empty($this->hosturl)) {
			$hosturl = array_reverse($this->hosturl);
			return $hosturl[$this->config['domainLevel']];
		}
	}
	
	public function getDomainAlias () {
		if (!empty($this->hosturl)) {
			$url = implode('.', $this->hosturl);
			foreach ($this->domains as $domainAlias=>$domains) {
				if (is_int(array_search($url, $domains)))
					return $domainAlias;
			}
			return $this->default['domainAlias'];
		}
	}
	
	public function getArguments()
	{
		return $this->url;
	}
	
	public function getCurrenPageWithoutQueryString() 
	{
		return str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
	}
	
	public function isCurrentPage($page)
	{
		return ( $this->getCurrenPageWithoutQueryString() == $page );
	}


	public function isDeveloperDomain()
	{
		return in_array(implode('.', $this->hosturl), $this->developerDomains);
	}
	
	public function isProductionDomain()
	{
		return (implode('.', $this->hosturl)  == $this->getDomainAlias());
	}
}
?>
