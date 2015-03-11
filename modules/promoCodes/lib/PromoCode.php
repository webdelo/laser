<?php
namespace modules\promoCodes\lib;
class PromoCode extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new PromoCodeObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\categories\CategoryDecorator($object);
		parent::__construct($object);
	}
	
	public function getName()
	{
		return $this->getParentObject()->name;
	}
	
	public function getDiscount()
	{
		return $this->getParentObject()->discount;
	}
	
	public function getDiscountPrice($price)
	{
		return round($price / 100 * ($this->getParentObject()->discount));
	}
	
	public function getCode()
	{
		return $this->getParentObject()->code;
	}
	
	public function getExpirationDate()
	{
		return $this->getParentObject()->expirationDate;
	}
	
	public function getQuantity()
	{
		return $this->getParentObject()->quantity;
	}
	
	public function getUser()
	{
		return \core\authorization\UserFactory::getInstance()->getUserById($this->getParentObject()->userId);
	}
}
