<?php
namespace core;
class ObjectPool
{
	static protected $instance = null;

	private $pool = array();

	private static function init()
	{
		self::$instance = new ObjectPool();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	private function __construct()
	{

	}

	public function &getObject($className, $objectId = null, $config = null)
	{
		$code = $this->getObjectCode($className, $objectId, $config);
		if ($this->checkObjectInPool($code)) {
			$object = &$this->getObjectFromPool($code);
			return $object;
		}
		$this->setObjectInPool($code, new Noop());
		$object = $this->instantObject($className, $objectId, $config);
		$this->setObjectInPool($code, $object);
		$object = &$this->getObjectFromPool($code);
		return $object;
	}

	private function getObjectCode($className, $objectId, $config)
	{
		$this->checkClassName($className);
		$table = (is_object($config)) ? '-'.$config->mainTable() : '';
		return $className.'-'.$objectId.$table;
	}

	private function checkClassName($className)
	{
		if (empty($className))
			throw new \Exception('Classname is empty in '.get_class($this).'!');
		return $this;
	}

	private function checkObjectInPool($code)
	{
		return isset($this->pool[$code]);
	}

	private function &getObjectFromPool($code)
	{
		$this->updateObjectLoad($code);
		$link = &$this->pool[$code]['object'];
		return $link;
	}

	private function updateObjectLoad($code, $unset = false)
	{
		$counter = ($unset) ? -1 : 1;
		$this->pool[$code]['externalLinks'] += $counter;
		return $this;
	}

	private function instantObject($className, $objectId, $config)
	{
		return new $className($objectId, $config);
	}

	private function setObjectInPool($code, $object)
	{
		if (isset($this->pool[$code])) {
			$this->pool[$code]['object'] = clone $object;
		} else {
			$this->pool[$code] = array(
				'object' => $object,
				'externalLinks' => 0,
			);
		}
		return $this;
	}

	public function unsetObject($className, $objectId, $config)
	{
		$code = $this->getObjectCode($className, $objectId, $config);
		if (isset($this->pool[$code]))
			if ($this->pool[$code]['externalLinks'] > 1)
				$this->updateObjectLoad($code, 'unset');
			else
				unset($this->pool[$code]);
		return true;
	}

	public function unsetPool()
	{
		unset($this->pool);
	}

	public function getStatistic()
	{
		if (empty($this->pool))
			return false;
		foreach ($this->pool as $key=>$object)
			$stats[$key] = $object['externalLinks'];
		return $stats;
	}
}