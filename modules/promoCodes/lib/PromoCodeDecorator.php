<?php
namespace modules\promoCodes\lib;
class PromoCodeDecorator extends \core\modules\base\ModuleDecorator
{
	private $promoCode;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getPromoCode()
	{
	    if(empty($this->promoCode)){
			$this->promoCode = ($this->promoCodeId) ? new PromoCode($this->promoCodeId) : $this->getNoop();
	    }

	    return $this->promoCode;
	}
}
