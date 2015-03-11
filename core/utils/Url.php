<?php
namespace core\utils;
class Url
{
	public static function getDomainByUrl($url, $clean = false)
	{
		$domainArray = explode('/', $url);
		$urlDomain = array_shift($domainArray);
		if ($clean){
			$urlDomain = str_replace(array('http://', 'www.', '/'), '', $urlDomain);
		}
		return $urlDomain;
	}
	
	public static function getSecondDomainLevelByUrl($url)
	{
		return self::getSecondDomainLevel(self::getDomainByUrl($url, true));
	}
	
	public static function getSecondDomainLevel($domain)
	{
		$levels = explode('.', $domain);
		$firstLevel = array_pop($levels);
		$secondLevel = array_pop($levels);
		return $secondLevel.'.'.$firstLevel;
	}
}