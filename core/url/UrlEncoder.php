<?php
namespace core\url;
class UrlEncoder
{
	use \core\traits\RequestHandler;
	
	static protected $object = null;
	private $domainName    = '';
	private $uri    = '';
	private $domain = '';
	private $controller = '';
	private $part = '';
	private $lang = '';
	private $action = '';
	private $protocol = '';
	
	private $parts = '';
	private $langs = '';
	private $currents = '';
	private $config = '';
	private $default = '';
	private $extensions = '';
	private $defaultConfig = array(
		'settings'=>
			array(
				'lang' => 'Element', // 'SubDomain', 'Element', 'None'
				'part' => 'SubDomain', // 'SubDomain', 'Element', 'None'
				'controller' => 'Element', // 'Element', 'None'
			),
		'default'=>
			array(
				'lang'=>'en', 
				'controller'=>'',
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
	);
	
	public function __construct()
	{
		
	}
	
	public function reset () 
	{
		$this->uri = '';
		$this->domain = '';
		$this->controller = '';
		$this->part = '';
		$this->lang = '';
		$this->action = '';
		$this->protocol = '';	
	}
	
	static public function getInstance ()
	{
		if (is_null(self::$object))
			self::$object = new UrlEncoder();
		return self::$object;
	}

	function getObject()
	{
		return self::$object;
 	}
	
	public function setConfig($config) {	
		if (!$config  || !is_array($config))
			$config = $this->getDefaultConfig();		
		
		$this->config = $config['settings'];
		$this->default = $config['default'];
		$this->currents = $config['currents'];
		$this->langs = $config['langs'];
		$this->parts = $config['parts'];
		$this->extensions = $config['extensions'];
		
		$this->setDomainName();
		
		return self::$object;
	}
	
	private function setDomainName()
	{
		$host = explode('.', $this->getSERVER()['HTTP_HOST']);
		$host = array_reverse($host);
		$thirdLevel = (isset($host[2])) ? $host[2] : null;
		$this->domainName = $this->isThirdLevel($thirdLevel).$host[1].'.'.$host[0]; 
	}
	
	private function isThirdLevel($var)
	{
		return ( $this->isLang($var) || $this->isPart($var) ) ? $var.'.' : '' ;	
	}
	
	private function onSubDomain($var)
	{
		return ($this->config[$var]=='SubDomain');
	}
	
	private function isLang($var)
	{
		return ( in_array ($var, $this->langs) );
	}
	
	private function isPart($var)
	{
		return ( in_array ($var, $this->parts) );
	}
	
	private function getDefaultConfig()
	{
		return $this->defaultConfig;
	}
	
	private function setCurrents()
	{
		$this->setPart($this->currents['part']);
		$this->setLang($this->currents['lang']);
		$this->setController($this->currents['controller']);
	}
	
	function setUrl($action, $data)
	{  
		$this->setCurrents();
		$this->setAction((string)$action);
		$this->setData((array)$data);
		$methods = array(
			'setLang'.$this->config['lang'],
			'setPart'.$this->config['part'],
			'setController'.$this->config['controller'],
		);
		foreach($methods as $method)
			$this->$method();
		
		$this->setActionElement();
		$this->setDataElement();
		
		$result = $this->getUrlString();
		$this->reset();
		return $result;
	}
	
	private function getUriString()
	{
		$this->removeEmptyFields($this->uri);
		return '/'.implode('/', $this->uri).'/';
	}
	
	private function getDomainString()
	{
		$this->removeEmptyFields($this->domain);
		if (is_array($this->domain))
			arsort($this->domain);
		return ($this->domain) ? implode('.', $this->domain).'.' : '';
	}
	
	private function removeEmptyFields(&$array) {
		if (is_array($array))
			foreach(array_keys($array, '') as $key)
				unset($array[$key]);
	}
	
	private function getUrlString()
	{
		$type = (TYPE=='admin')?'/'.TYPE:'';
		return $this->getProtocol().$this->getDomainString().$this->domainName.$type.$this->getUriString();
	}
	
	private function getProtocol () 
	{
		return ($this->protocol) ? $this->protocol : 'http://' ;
	}

	public function printUrl($action, $data)
	{
		echo $this->setUrl($action, $data);
	}
	
	public function setController($name)
	{
		$this->controller = $name;
		return $this;
	}
	
	public function setPart($name)
	{
		$this->part = $name;
		return $this;
	}

	public function setLang($name)
	{
		$this->lang = $name;
		return $this;
	}
	
	public function setAction ($name)
	{
		$this->action = str_replace('/', '', $name);;
	}
	
	public function setData ($data)
	{
		$this->data = $data;
	}
	
	public function setProtocol ($data)
	{
		$this->protocol = $data;
	}
	
	public function setCurrentController($name)
	{
		$this->currents['controller'] = $name;
		return $this;
	}
	
	public function setCurrentPart($name)
	{
		$this->currents['part'] = $name;
		return $this;
	}

	public function setCurrentLang($name)
	{
		$this->currents['lang'] = $name;
		return $this;
	}
	
	
	public function setLangNone ()
	{
		return '';
	}

	public function setLangElement ()
	{
		$this->uri[] = (in_array($this->lang, $this->langs)) ? $this->lang : '' ;
	}

	public function setLangSubDomain ()
	{
		$this->domain[] = (in_array($this->lang, $this->langs)) ? $this->lang : '' ;
	}
	
	public function setPartNone ()
	{
		return '';
	}

	public function setPartElement ()
	{
		$this->uri[] = (in_array($this->part, $this->parts)) ? $this->part : '' ;
	}

	public function setPartSubDomain ()
	{
		$this->domain[] = (in_array($this->part, $this->parts)) ? $this->part : '' ;
	}
	
	public function setControllerNone ()
	{
		return '';
	}

	public function setControllerElement ()
	{
		$this->uri[] = $this->controller;
	}

	public function setControllerSubDomain ()
	{
		$this->domain = $this->controller;
	}
	
	public function setActionElement ()
	{
		$this->uri[] = $this->action;
	}
	
	public function setDataElement ()
	{
		$this->uri = array_merge($this->uri, $this->data);
	}
	
}