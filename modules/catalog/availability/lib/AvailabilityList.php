<?php
namespace modules\catalog\availability\lib;
class AvailabilityList extends \core\modules\base\ModuleDecorator
{
	private $status;
	
	function __construct($object)
	{	
		$object = new AvailabilityListObject($object);
		parent::__construct($object);
	}
	
	function getStatus()
	{
	    if(empty($this->status)){
			$totalQuantity = $this->getTotalQuantity();
			if ($totalQuantity > 2)
				$statusId = 1;
			elseif ($totalQuantity >= 1)
				$statusId = 2;
			elseif ($this->isManufacturingExists())
				$statusId = 3;
			else
				$statusId = 4;
			
			$this->status = $this->getObject('\core\modules\statuses\Status', $statusId, $this);
		}
	    return $this->status;
	}
}