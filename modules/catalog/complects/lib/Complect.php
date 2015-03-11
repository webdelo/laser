<?php
namespace modules\catalog\complects\lib;
class Complect extends \modules\catalog\CatalogGood implements \interfaces\IGoodForShopcart, \interfaces\IGoodForOrder
{
	use \core\traits\RequestHandler;

	function __construct($objectId)
	{
		$object = new ComplectObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		parent::__construct($object);
	}

	public function remove () {
            return ($this->delete()) ? (int)$this->id : false ;
	}

	public function getComplectGoods()
	{
		$complectGoods = new \modules\catalog\complects\complectGoods\lib\ComplectGoods();
		return $complectGoods->getGoodsByComplectId($this->id);
	}

	public function getPrimaryGood()
	{
		$complectGoods = $this->getComplectGoods();
		$complectGoods->setSubquery('AND `complectId` = ?d', $this->id)
					->setSubquery('AND `mainGood` = ?d', 1)
					->setLimit('1');
		foreach ( $complectGoods as $complectGood )
			return $complectGood;
	}

	public function getSecondaryGood()
	{
		$complectGoods = $this->getComplectGoods();
		$complectGoods->setSubquery('AND `complectId` = ?d', $this->id)
					->setSubquery('AND `mainGood` = ?d', 0)
					->setLimit('1');
		foreach ( $complectGoods as $complectGood )
			return $complectGood;
	}

	public function getSecondaryGoods($quantity = null)
	{
		$complectGoods = $this->getComplectGoods();
		$complectGoods->setSubquery('AND `complectId` = ?d', $this->id)
					->setSubquery('AND `mainGood` = ?d', 0)
					->setOrderBy('`discount` ASC');
		if($quantity)
			$complectGoods->setLimit($quantity);
		return $complectGoods;
	}

	public function getComplectManager()
	{
		return $this->managerId
				? $this->getObject('\modules\administrators\lib\Administrator', $this->managerId)
				: $this->getNoop();
	}

	/* Start: IGoodForSale Methods */
	public function getMinQuantity()
	{
		return 1;
	}

	public function getPriceByQuantity($quantity)
	{
		return $this->getComplectGoods()->getTotalSum();
	}

	public function getPriceByMinQuantity()
	{
		return $this->getPriceByQuantity($this->getMinQuantity());
	}

	public function getPathToShopcartGoodTemplate()
	{
		return $this->getConfig()->shopcartTemplate;
	}
	/* End: IGoodForShopcart Methods */

	/* Start: IGoodForOrder Methods */
	public function getBasePriceByQuantity($quantity)
	{
		return $this->getComplectGoods()->getTotalBaseSum();
	}

	public function getBasePriceByQuantityForPartner($quantity, $partnerId)
	{
		
	}

	public function getBasePriceByMinQuantity()
	{
		return $this->getBasePriceByQuantity($this->getMinQuantity());
	}

	public function getPathToOrderGoodTemplate()
	{
		 return $this->getConfig()->orderTemplate;
	}

	public function getPathToAdminOrderGoodTemplate()
	{
		return $this->getConfig()->orderGoodAdminTemplate;
	}

	public function getTotalAvailability()
	{
		$primaryQuantity = $this->getPrimaryGood()->getGood()->getAvailabilityList()->getTotalQuantity();
		$quantities = array($primaryQuantity);
		foreach($this->getComplectGoods() as $good)
			$quantities[] = $good->getGood()->getAvailabilityList()->getTotalQuantity();
		return min($quantities);
	}
	/* End: IGoodForOrder Methods */
	/* Start: URL Methods */
	public function getAdminURL()
	{
		return '/admin/complects/complect/'.$this->id.'/';
	}
	public function getPath()
	{
		return $this->getPrimaryGood()->getGood()->getPath();
	}
	/*   End: URL Methods */

	public function getFirstPrimaryImage()
	{
		return $this->getPrimaryGood()->getGood()->getFirstPrimaryImage();
	}

	public function getAdminPath()
	{
		return '/admin/complects/complect/'.$this->id.'/';
	}
}
