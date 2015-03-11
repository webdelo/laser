<?php
namespace modules\catalog\colors\lib;
class ColorsGroupsDecorator extends \core\modules\base\ModuleDecorator
{
	private $groups;
	private $groupsByGoodName = array();

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getGroups()
	{
	    if(empty($this->groups)){
			$this->groups = new ColorsGroups($this->getParentObject());
			$this->groups->setOrderBy('`priority` ASC');
	    }

	    return $this->groups;
	}
	
	public function getGroupsByGoodName($name)
	{
		if (empty($this->groupsByGoodName[$name])) {
			$this->groupsByGoodName[$name] = $this->getGroups();
			$this->groupsByGoodName[$name]->setSubquery(' AND `name`=\'?s\' ', $this->getGroups()->truncateLastWord($name));
		}
		return $this->groupsByGoodName[$name];
	}
}
