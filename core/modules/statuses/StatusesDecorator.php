<?php
namespace core\modules\statuses;
class StatusesDecorator extends \core\modules\base\ModuleDecorator
{

	protected $statuses;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getStatuses()
	{
	    if(empty($this->statuses))
		$this->statuses = new Statuses($this->getParentObject());

	    return $this->statuses;
	}
}
