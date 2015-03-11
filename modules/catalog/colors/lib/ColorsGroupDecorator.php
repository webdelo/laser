<?php
namespace modules\catalog\colors\lib;
class ColorsGroupDecorator extends \core\modules\base\ModuleDecorator
{
	private $group;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getGroup()
	{
	    if(empty($this->group)){
			$this->group = new ColorsGroup($this->colorGroupId, $this->getParentObject());
	    }

	    return $this->group;
	}
}
