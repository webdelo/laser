<?php
namespace core\modules\base;
class ParentDecorator extends ModuleDecorator
{
	private $parent;
	private $parentClass;
	private $configObject;

	function __construct($object, $configObject = null)
	{
		parent::__construct($object);
		$this->configObject = $configObject;
	}

	public function getParent()
	{
		if (empty($this->parent))
			$this->getParentClass($this->getParentObject())->instantParentObject($this->getParentObject()->parentId, $this->configObject);
		return $this->parent;
	}

	private function getParentClass($object)
	{
		$this->parentClass = str_replace('Object', '', get_class($object));
		return $this;
	}

	private function instantParentObject($parentId, $configObject = null)
	{
		return $this->parent = !empty($parentId) ? $this->getObject($this->parentClass, $parentId, $configObject) : $this->getNoop();
	}

	public function getChildren($statusesArray = array(), $excludedId = array())
	{
		if (!is_array($statusesArray))
			$statusesArray = array((int)$statusesArray);
		$config = clone $this->getConfig();
		$config->removePostfix();
		$className = $config->getObjectsClass();
		$objects = new $className($config);
		if (!empty($statusesArray))
			$objects->setSubquery(' AND `statusId` IN (?s)',  implode(',', $statusesArray));
		if (!empty($excludedId))
			$objects->setSubquery(' AND `id` NOT IN (?s)',  implode(',', $excludedId));
		$objects->setSubquery(' AND `parentId`= ?d',$this->getParentObject()->id)
				->setOrderBy('`priority` ASC');
		if($objects->count() == 0)
		    return false;

		return $objects;
	}

	public function getChildrenIdString($statusesArray = array(), $excludedId = array())
	{
		$children = $this->getChildren($statusesArray, $excludedId);
		if(!$children)
			return false;

		$idString = $this->getChildrenIdStringRecursive($this, $statusesArray, $excludedId);
		return substr($idString, 0, strlen($idString)-1);

	}

	//	на уровне базы делать выборку id

	private function getChildrenIdStringRecursive($category, $statusesArray, $excludedId, &$idString = '')
	{
		foreach($category->getChildren($statusesArray, $excludedId) as $child){
			$idString .= $child->id.',';
			if($child->getChildren($statusesArray, $excludedId))
				$this->getChildrenIdStringAction($child, $statusesArray, $excludedId, $idString);
		}
		return $idString;
	}
}