<?php
namespace modules\promoCodes\lib;
class PromoCodes extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new PromoCodesObject;
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}
	
	public function getPromoCodeByCode($code)
	{
		$promoCodeId = $this->getIdByCode($code);
		return $promoCodeId
			? $this->getObjectById($promoCodeId)
			: $this->getNoop();
	}
}