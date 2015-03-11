<?php
namespace modules\catalog\basePrices\lib;
class BasePricesDecorator extends \core\modules\base\ModuleDecorator
{
	private $basePrices;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getBasePrices()
	{
		if (empty($this->basePrices))
			$this->basePrices = new BasePrices($this->getParentObject());
		return $this->basePrices;
	}
}
