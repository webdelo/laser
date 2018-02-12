<?php
/* Requires \core\traits\ObjectPool in parent object */
namespace core\modules\groups;
trait GroupTraitDecorator
{
	private $group;

	public function getGroup()
	{
		$this->checkGroupTraitsRequires();
		if(empty($this->group))
			$this->group = $this->getObject('\core\modules\groups\Group', $this->groupId);
		return $this->group;
	}

	private function checkGroupTraitsRequires()
	{
		if (in_array('getObject', get_class_methods($this)))
			return $this;
		throw new \Exception('Requires implementation of the method "getObject" for trait "\core\modules\groups\GroupTraitDecorator" in object '.get_class($this).'!');
	}
}