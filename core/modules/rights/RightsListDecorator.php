<?php
namespace core\modules\rights;
class RightsListDecorator extends \core\modules\base\ModuleDecorator
{
	public $rights;
	
	function __construct($object)
	{
		parent::__construct($object);
		$this->instantRights($this->id);
	}
	
	function instantRights($categoryId)
	{
		return $this->rights = new RightsList($this->id, $this->_object);
	}

}