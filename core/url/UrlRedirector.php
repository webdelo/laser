<?php
namespace core\url;
class UrlRedirector
{
	use \core\traits\RequestHandler;

	static protected $instance = null;

	private $config;
	private $redirectInfo;
	private $protocol;
	private $www;
	private $domain;
	private $subdomain;
	private $uri;
	private $queryString;
	private $url;
	private $constantAdresPart = false;

	private static function init()
	{
		self::$instance = new UrlRedirector();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	protected function __construct()
	{
		$this->setConfig()
			 ->setCurrentProtocol()
			 ->setCurrentNetName()
			 ->setCurrentDomain()
			 ->setCurrentSubdomain()
			 ->setCurrentUri()
			 ->setCurrentQueryString()
			 ->setCurrentUrl();
	}

	public function setConfig($config = null)
	{
		if (!$config  ||  !is_array($config))
			$config = $this->getDefaultConfig();
		$this->config = $config;
		return $this;
	}

	private function getDefaultConfig()
	{
		return array(
			'csvPath'        => '',
			'www'            => 'with',
			'http2https'     => array(),
			'https2http'     => true,
			'wwwSubdomains'  => 'without',
			'subdomainLevel' => 2,
			'endSlash'       => true,
		);
	}

	public function loadCsvData()
	{
		$csvArray = $this->loadCsvFile();
		$this->parseCsvData($csvArray);
		return $this;
	}

	private function loadCsvFile()
	{
		$csvFile = $this->config['csvPath'].$this->getCurrentDomainAlias().'.'.$this->config['csvFile'];
		if (file_exists($csvFile))
			return file($csvFile);

		$defaultFile = $this->config['csvPath'].$this->config['csvFile'];
		if(file_exists($defaultFile))
			return file($defaultFile);

		throw new Exception('CSV redirect file was not found in '.$csvFile.'!', 1);
	}

	private function parseCsvData($csvArray)
	{
		foreach ($csvArray as $val){
			$val = array_map('trim', explode(';', $val));
			$val[0] = $this->clearUrl($val[0]);
			$val[1] = $this->clearUrl($val[1]);
			if ($val[0] && $val[1] && $val[0]!=$val[1]){
				$this->redirectInfo['current'][] = $val[0];
				$this->redirectInfo['redirect'][] = $val[1];
			}
		}
	}

	private function clearUrl($url)
	{
		$url = trim($url);
		$dir_http = str_replace(array('http://','www.'), '', DIR_HTTP);
		$explodeArgs = array(
			'http://www.'.$dir_http,
			'http://'.$dir_http,
			'www.'.$dir_http
		);
		$url =  str_replace($explodeArgs, '', $url);
		if (!$this->isHttp($url) && !$this->isHttps($url)){
			$url = $this->removeFirstSlasheRecursive($url);
			$url = DIR_HTTP.$url;
		}
		return $url;
	}

	private function isHttp($url)
	{
		return (substr($url,0,7) == 'http://') ? true : false;
	}

	private function isHttps($url)
	{
		return (substr($url,0,8) == 'https://') ? true : false;
	}

	private function removeFirstSlasheRecursive($url)
	{
		if ($url)
			if ($url[0] == '/')
				$url = $this->removeFirstSlasheRecursive(substr($url,1));
		return $url;
	}

	private function setCurrentProtocol()
	{
		$this->protocol = (@$_SERVER['HTTPS'] && @$_SERVER['HTTPS']!='off') ? 'https' : 'http';
		return $this;
	}

	private function setProtocol($value)
	{
		$this->protocol = $value;
		return $this;
	}

	private function setCurrentNetName()
	{
		$this->www = (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') ? 'www.' : '';
		return $this;
	}

	private function setNetName($value)
	{
		$this->www = $value;
		return $this;
	}

	private function setCurrentDomain()
	{
		$this->domain = str_replace('www.', '', $_SERVER['HTTP_HOST']);
		return $this;
	}

	private function setCurrentSubdomain()
	{
		$domains = explode('.', $this->domain);
		$this->subdomain = ( sizeof($domains) > ($this->config['subdomainLevel']) ) ? $domains[0] : NULL;
		return $this;
	}

	private function setCurrentUri()
	{
		$this->uri = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
		return $this;
	}

	private function setCurrentQueryString()
	{
		$this->queryString = $_SERVER['QUERY_STRING'];
		return $this;
	}

	private function setCurrentUrl()
	{
		$this->url = rtrim($this->protocol.'://'.$this->www.$this->domain.$this->uri, '/');
		if (!$this->isFileRequest())
			$this->url .= '/';
		return $this;
	}

	private function isFileRequest()
	{
		$dirs = explode('/', $this->uri);
		$size = count(explode('.', array_pop($dirs)));
		return $size != 1;
	}

	public function redirectCurrentPage()
	{
		$this->checkCsvRedirect()
			 ->checkWwwRedirect()
			 ->checkHttps2HttpRedirect()
			 ->checkHttp2HttpsRedirect()
			 ->checkEndSlashRedirect();
		return true;
	}

	private function checkCsvRedirect()
	{
		if ($this->redirectInfo['current']){
			$redirectKey = $this->getSimpleRedirectKey() ? $this->getSimpleRedirectKey() : $this->getPatternRedirectKey();
			if (is_int($redirectKey))
				$this->redirect($this->redirectInfo['redirect'][$redirectKey - 1]);
		}
		return $this;
	}

	private function getSimpleRedirectKey()
	{
		$url = urldecode($this->url . ($this->queryString ? '?'.$this->queryString : ''));

		$redirectKey = array_search($url, $this->redirectInfo['current']);

		if(!is_int($redirectKey))
			$redirectKey = array_search(substr($url, 0, strlen($url)-1), $this->redirectInfo['current']);

		if(!is_int($redirectKey))
			$redirectKey = array_search($url.'/', $this->redirectInfo['current']);

		return is_int($redirectKey) ? $redirectKey + 1 : false;
	}

	private function getPatternRedirectKey()
	{
		$adres = urldecode('http://'.$this->domain.$_SERVER['REQUEST_URI']);
		foreach($this->redirectInfo['current'] as $element){
			if(strpos($element, '*')){
				$elementPart = explode('*', urldecode($element));
				$constantAdresPart = urldecode(str_replace($elementPart[0], '', str_replace($elementPart[1], '', $adres)));
				if( substr_count($adres, $element[0]) && str_replace('*', $constantAdresPart, $element)===$adres ){
					$redirectKey = array_search($element, $this->redirectInfo['current']);
					$this->setConstantAdresPart($constantAdresPart);
				}
			}
		}
		return (isset($redirectKey) && is_int($redirectKey)) ? $redirectKey + 1: false;
	}

	private function setConstantAdresPart($constantAdresPart)
	{
		$this->constantAdresPart = $constantAdresPart;
	}

	private function redirect($redirectUrl)
	{
		if($this->checkUrl($redirectUrl))
			$this->setHeaders( $this->checkConstantAdresPart($redirectUrl) );
	}

	private function checkConstantAdresPart($redirectUrl)
	{
		if($this->constantAdresPart){
			$redirectUrl = str_replace('http:/', 'http://', str_replace('//', '/', str_replace('*', $this->constantAdresPart, $redirectUrl))) ;
		}
		return $redirectUrl;
	}

	private function checkUrl($redirectUrl)
	{
		return ($redirectUrl) ? true : false;
	}

	private function setHeaders($redirectUrl)
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $redirectUrl );
		die();
	}

	private function checkWwwRedirect()
	{
		$redirectMode = (empty($this->subdomain)) ? $this->config['www'] : $this->config['wwwSubdomains'];
		$methodName = 'redirect'.ucfirst($redirectMode).'Www';
		if (method_exists($this,$methodName))
			$this->$methodName();
		return $this;
	}

	private function redirectWithWww()
	{
		if (!$this->www)
			$this->redirect($this->setNetName('www.')->setCurrentUrl()->url);
	}

	private function redirectWithoutWww()
	{
		if ($this->www)
			$this->redirect($this->setNetName('')->setCurrentUrl()->url);
	}

	private function checkHttps2HttpRedirect()
	{
		if ($this->protocol == 'https' && !$this->checkHttp2Https() && $this->config['https2http'])
			$this->redirectHttps2Http();
		return $this;
	}

	private function checkHttp2Https()
	{
		foreach ($this->config['http2https'] as $pattern){
			$pattern = $this->www.$this->domain.str_replace('*', '', $pattern);
			if (strpos($this->url, $pattern) !==false)
				return true;
		}
		return false;
	}

	private function redirectHttps2Http()
	{
		$this->redirect($this->setProtocol('http')->setCurrentUrl()->url);
	}

	private function checkHttp2HttpsRedirect()
	{
		if ($this->checkHttp2Https() && $this->protocol != 'https'){
			$this->redirectHttp2Https();
		}
		return $this;
	}

	private function redirectHttp2Https()
	{
		$this->redirect($this->setProtocol('https')->setCurrentUrl()->url);
	}

	private function checkEndSlashRedirect()
	{
		if ($this->config['endSlash'])
			if ($this->getLastSimbol($this->uri) != '/' && !$this->isFileRequest())
				$this->redirect($this->setCurrentUrl()->url);
		return $this;
	}

	private function getLastSimbol($str)
	{
		return strlen($str) ? $str[strlen($str)-1] : '';
	}
}
