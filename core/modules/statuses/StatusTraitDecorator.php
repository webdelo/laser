<?php
/* Requires \core\traits\ObjectPool in parent object */
namespace core\modules\statuses;
trait StatusTraitDecorator
{
	private $status;

	public function getStatus()
	{
		$this->checkStatusTraits();
	    if(empty($this->status))
			$this->status = $this->getObject('\core\modules\statuses\Status', $this->statusId, $this);
	    return $this->status;
	}

	private function checkStatusTraits()
	{
		if (in_array('getObject', get_class_methods($this)))
			return $this;
		throw new \Exception('Requires implementation of the method "getObject" for trait "\core\modules\statuses\StatusTraitDecorator" in object '.get_class($this).'!');
	}
}