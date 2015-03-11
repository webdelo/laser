<?php
namespace core;
class SessionFilters
{
	const SESSION_KEY = 'filters';
	static protected $instance = null;

	public static function init()
	{
		self::$instance = new SessionFilters();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	protected function __construct(){}

	public function set($key,$params)
	{
		$array = new ArrayWrapper($params);
		 $_SESSION[self::SESSION_KEY][$key] = serialize($array);
	}

	public function get($key,$param)
	{
		if(isset($_SESSION[self::SESSION_KEY][$key])){
			$array = unserialize($_SESSION[self::SESSION_KEY][$key]);
			return $array[$param];
		} else {
			return false;
		}
	}

	public function clear($key)
	{
		if(isset($_SESSION[self::SESSION_KEY][$key])){
			unset($_SESSION[self::SESSION_KEY][$key]);
		}
	}
}

?>