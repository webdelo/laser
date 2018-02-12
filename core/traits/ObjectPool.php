<?php
namespace core\traits;
trait ObjectPool
{
	protected function getObject($className, $objectId = null, $config = null)
	{
		return \core\ObjectPool::getInstance()->getObject($className, $objectId, $config);
	}

	protected function unsetObject($className, $objectId = null, $config = null)
	{
		return \core\ObjectPool::getInstance()->unsetObject($className, $objectId, $config);
	}

	protected function getNoop()
	{
		return \core\ObjectPool::getInstance()->getObject('\core\Noop');
	}
	
	protected function isNoop($object)
	{
		return $object instanceof \core\Noop;
	}
	
	protected function isNotNoop($object)
	{
		return !$this->isNoop($object);
	}
}