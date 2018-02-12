<?php
namespace core\modules\groups;
class GroupDecorator extends \core\modules\base\ModuleDecorator
{
	protected $group;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getGroup()
	{
		if(empty($this->group))
			$this->group = $this->getObject('\core\modules\groups\Group', $this->groupId);
		return $this->group;
	}

}