<?php
namespace core\modules\statuses;
class statusDecorator extends \core\modules\base\ModuleDecorator
{
	public $status;
	
	function __construct($object)
	{
		parent::__construct($object);
	}
	
	function getStatus()
	{
	    if(empty($this->status))
			$this->status = $this->getObject('\core\modules\statuses\Status', $this->getParentObject()->statusId, $this);
	    
	    return $this->status;
	}
}