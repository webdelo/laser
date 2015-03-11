<?php
namespace core\modules\groups;
class GroupsDecorator extends \core\modules\base\ModuleDecorator
{
	protected $groups;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getGroups()
	{
	    if(empty($this->groups))
		$this->groups = new Groups($this->getParentObject());

	    return $this->groups;
	}

}