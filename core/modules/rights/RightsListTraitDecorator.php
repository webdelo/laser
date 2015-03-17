<?php
namespace core\modules\rights;
trait RightsListTraitDecorator
{
	private $rights;

	public function getRights()
	{
		if (empty($this->rights))
			$this->rights = new RightsList($this->id, $this);
		return $this->rights;
	}
}