<?php
namespace core;
class Configurator implements \Countable, \Iterator
{

	protected $data = array();
	static protected $instance = null;

	public static function init(Array $configArray = array())
	{
		self::$instance = new Configurator($configArray);
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	protected function __construct(Array $data)
	{
		$this->load($data);
	}

	public function load(Array $data)
	{
		$this->data = array_merge($this->data, $data);
	}

	public function getArrayByKey($key)
	{
		if (isset($this->data[$key]))
			return (array)$this->data[$key];
		throw new \Exception('Key "' . $key . '" was not found in config file!');
	}
	
	public function getArray()
	{
		return (array)$this->data;
	}

	public function __get($name)
	{
		if (!array_key_exists($name, $this->data)) {
			trigger_error('Unknown key in configuration: ' . $name, E_USER_NOTICE);
			return null;
		}

		if (!is_array($this->data[$name]))
			return $this->data[$name];

		return new Configurator($this->data[$name]);
	}

	public function __set($name, $value)
	{
		$this->data['name'] = $value;
	}

	public function count()
	{
		return count($this->data);
	}

	public function __isset($name)
	{
		return isset($this->data[$name]);
	}

	public function __unset($name)
	{
		if (array_key_exists($name, $this->data))
			unset($this->data[$name]);
	}

	public function rewind()
	{
		reset($this->data);
	}

	public function current()
	{
		$key = key($this->data);
		return $this->__get($key);
	}

	public function key()
	{
		return key($this->data);
	}

	public function next()
	{
		next($this->data);
		$key = key($this->data);
		return $this->__get($key);
	}

	public function valid()
	{
		$key = key($this->data);
		return ((false !== $key) && (null !== $key));
	}

}