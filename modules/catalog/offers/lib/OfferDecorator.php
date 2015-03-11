<?php
namespace modules\catalog\offers\lib;
class OfferDecorator extends \core\modules\base\ModuleDecorator
{
	private $offer;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getOffer()
	{
		if (empty($this->offer)){
			$offers = new \modules\catalog\offers\lib\Offers;
			$this->offer = $offers->getValidOfferByGoodId($this->id);
		}
		return $this->offer ? $this->offer : false;
	}
}
