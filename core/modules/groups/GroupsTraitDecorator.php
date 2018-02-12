<?php
namespace core\modules\groups;
trait GroupsTraitDecorator
{
	private $groups;

	public function getGroups()
	{
	    if(empty($this->groups))
			$this->groups = new Groups($this);
	    return $this->groups;
	}
}