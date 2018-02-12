<?php
namespace core\modules\statuses;
trait TranslateStatusesTraitDecorator
{
	private $statuses;

	public function getStatuses()
	{
	    if(empty($this->statuses))
			$this->statuses = new TranslateStatuses($this);
	    return $this->statuses;
	}
}
