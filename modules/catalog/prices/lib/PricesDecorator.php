<?php
namespace modules\catalog\prices\lib;
class PricesDecorator extends \core\modules\base\ModuleDecorator
{
	private $prices;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getPrices()
	{
		if (empty($this->prices))
			$this->prices = new Prices($this->getParentObject());
		return $this->prices;
	}
}
