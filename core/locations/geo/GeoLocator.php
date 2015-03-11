<?php
namespace core\locations\geo;
class GeoLocator
{
	private $ip;
	private $data = array();
	private $onlineServiceUrl = 'http://ipgeobase.ru:7020/geo?ip=';
	
	public function __construct($ip)
	{
		$this->checkIp($ip)
			->setIp($ip);
	}
	
	private function checkIp($ip)
	{
		if (!preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $ip))
			throw new \Exception('Submited invalid IP in GeoLocator Object.');
		return $this;
	}
	
	private function setIp($ip)
	{
		$this->ip = $ip;
		return $this;
	}
	
	public function __get($name)
	{
		$this->loadIpInfo();
		if (isset($this->data[$name]))
			return $this->data[$name];
		return NULL;
	}
	
	private function loadIpInfo()
	{
		if (!$this->data)
			$this->getInfoFromOnlineService();
		return $this;
	}
	
	private function getInfoFromOnlineService()
	{
		$xml = simplexml_load_file($this->onlineServiceUrl.$this->ip);
		$this->data['country'] = $xml->ip->country;
	}
}