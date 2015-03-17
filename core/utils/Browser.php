<?php
namespace core\utils;
class Browser
{
	private static $browserInfo;

	static private function getBrowscap()
	{
		return new \core\modules\browscap\Browscap(DIR.'tmp/browscap');
	}

	static public function isIE()
	{
		
		return ( self::getBrowscap()->getBrowser()->Browser == 'IE' );
	}
	
	static public function isChrome()
	{
		return ( self::getBrowscap()->getBrowser()->Browser == 'Chrome' );
	}
	
	static public function isFirefox()
	{
		return ( self::getBrowscap()->getBrowser()->Browser == 'Firefox' );
	}
	
	static public function isSafari()
	{
		return ( self::getBrowscap()->getBrowser()->Browser == 'Safari' );
	}
}
?>