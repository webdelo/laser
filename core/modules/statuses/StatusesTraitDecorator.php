<?php
namespace core\modules\statuses;
trait StatusesTraitDecorator
{
	private $statuses;

	public function getStatuses()
	{
	    if(empty($this->statuses))
			$this->statuses = new Statuses($this);
	    return $this->statuses;
	}
}
