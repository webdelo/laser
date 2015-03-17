<?php
namespace core\cache;
class Cacher
{
	static protected $instance = null;
	
	protected $enginesPool = array();
	protected $config      = array();
	
	private $_currentEngine;
	private $_defaultEngine;
	private $_defaultEngineFlag;
	
	protected function __construct(){
		$this->loadConfig();
	}
	
	protected function loadConfig()
	{
		$this->config = \core\Configurator::getInstance()->getArrayByKey('cache');
	}
	
	public static function init()
	{
		if (is_null(self::$instance))
			self::$instance = new Cacher();
		return self::$instance;
	}
	
	public static function getInstance($engine = null)
	{
		$cacher = self::init();
		if ($engine){
			$cacher->setEngine($engine);
		} else {
			$cacher->setDefaultEngine();
		}
		return $cacher;
	}
	
	private function setEngine($engine)
	{
		$this->_currentEngine     = (string)$engine;
		$this->_defaultEngineFlag = false;
		return $this;
	}
	
	private function getCurrentEngine()
	{
		if (!$this->checkEngineInPool())
			$this->instanceCurrentEngine();
		return $this->enginesPool[$this->_currentEngine];
	}
	
	private function checkEngineInPool()
	{
		return is_object($this->enginesPool[$this->_currentEngine]);
	}
	
	private function instanceCurrentEngine()
	{
		return $this->instanceEngine($this->_currentEngine);
	}
	
	private function instanceEngine($engineName)
	{
		$class = '\\core\\cache\\engines\\'.$engineName;
		if ($this->checkEngineClass($class)) {
			$this->enginesPool[$engineName] = new $class();
			return this;
		}
		throw new \Exception('Unknown cache engine "'.$engineName.'" in class '.get_class($this).'!');
	}
	
	private function checkEngineClass($class)
	{
		return class_exists($class, true);
	}
	
	private function setDefaultEngine()
	{
		$this->config['engines'] = ksort($this->config['engines']);
		$config = current(reset($this->config['engines']));
		$this->_currentEngine = $config['name'];
		$this->_defaultEngineFlag = true;
		return $this;
	}
	
	public function setCache($data, $key, $category)
	{
		
	}
	
	public function getCache($key, $category)
	{
		
	}
	
	public function removeCache($category, $key = null)
	{
		
	}
	
	public function resetCache()
	{
		
	}
}