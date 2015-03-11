<?php
namespace modules\shopcart\lib;
class Shopcart implements \Iterator, \Countable
{
	use traits\ShopcartBaseMethods,
		\core\traits\Errors;
	
	static protected $instance = null;
	protected $totalGoodsPrice = 0;

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::$instance = new Shopcart();
		return self::$instance;
	}

	public function __construct()
	{
		$this->getGoodsFromCookie();
	}

	protected function getGoodsFromCookie()
	{
		$this->resetGoods();
		if (isset($_COOKIE[$this->cookieGoodsKey])) {
			try {
				foreach($this->getUnserializedCookie() as $good)
					$this->setShopcartGood($good['objectClass'], $good['objectId'], $good['quantity']);
			} catch (ShopcartException $e) {
				$this->getGoodsFromCookie();
			}
		}
		return $this;
	}

	protected function setShopcartGood($objectClass, $objectId, $quantity)
	{
		$elementKey = $this->getElementKey($objectClass, $objectId);
		$shopcartGood = $this->getGood($elementKey);
		if (is_object($shopcartGood))
			$shopcartGood->setQuantity($shopcartGood->getQuantity()+$quantity);
		else
			$this->setGood($elementKey, new ShopcartGood($objectClass, $objectId, $quantity));
		return $this;
	}

	public function addGood($objectClass, $objectId, $quantity)
	{
		$objectClass = (string)$objectClass;
		$objectId    = (int)$objectId;
		$quantity    = (int)$quantity;
		try {
			$this->checkObjectClass($objectClass)
				->checkObjectId($objectId)
				->checkQuantityForGood($quantity, $this->getObject($objectClass, $objectId))
				->setShopcartGood($objectClass, $objectId, $quantity)
				->updateCookie();
			return true;
		} catch (\exceptions\ExceptionShopcart $e) {
			$this->setError('quantity_'.$objectId, $e->getTextError());
			return $this->getErrors();
		}
	}

	public function getTotalPrice()
	{
		if (empty($this->totalGoodsPrice)) {
			foreach ($this->getGoods() as $good)
				$this->totalGoodsPrice += $good->getTotalPrice();
		}
		return $this->totalGoodsPrice;
	}
	
	public function getShopcartPrice()
	{
		
		return $this->getTotalPrice() - $this->getPromoCodeDiscountSum() + $this->getDeliveryPrice();
	}

	public function getTotalQuantity()
	{
		$totalQuantity = 0;
		foreach ($this->getGoods() as $good)
			$totalQuantity += $good->getQuantity();
		return $totalQuantity;
	}

	public function resetShopcart()
	{
		return $this->resetGoods()->updateCookie();
	}

	public function removeGoodByCode($code)
	{
		$this->removeShopcartGoodByKey($code);
		return true;
	}

	/* Start: Iterator Methods */
	function rewind() {
		reset($this->getGoods());
//		reset($this->getSESSION()[$this->sessionsGoodsKey]);
	}

	function current() {
		return current($this->getGoods());
//		return current($this->getSESSION()[$this->sessionsGoodsKey]);
	}

	function key() {
		return key($this->getGoods());
//		return key($this->getSESSION()[$this->sessionsGoodsKey]);
	}

	function next() {
		next($this->getGoods());
//		next($this->getSESSION()[$this->sessionsGoodsKey]);
	}

	function valid() {
		return !!(current($this->getGoods()));
//		return !!(current($this->getSESSION()[$this->sessionsGoodsKey]));
	}
	/* End: Iterator Methods */

	/* Start: Countable Methods */
	function count()
	{
		return count($this->getGoods());
//		return count($this->getSESSION()[$this->sessionsGoodsKey]);
	}
	/* End: Countable Methods */

	public function isGoodsInShopcart()
	{
		return !!$this->count();
	}
	
	public function addPromoCode($promoCode) 
	{
		$promoCode = (string)$promoCode;		
		$promoCodes = new \modules\promoCodes\lib\PromoCodes();
		$promoObject = $promoCodes->getPromoCodeByCode($promoCode);
		if ($this->isNoop($promoObject)){
			return array('promoCode'=>$this->getErrorsList()['code_failed']);
		}
		if ($promoObject->statusId != \modules\promoCodes\lib\PromoCodeConfig::STATUS_ACTIVE){
			return array('promoCode'=>$this->getErrorsList()['code_blocked']);
		}
		$this->addPromoCodeInSession($promoObject->id);
		return true;
	}
	
	public function removePromoCode()
	{
		return $this->removePromoCodeFromSession();
	}
	
	public function getPromoCode()
	{
		$promoCodeId = $this->getPromoCodeFromSession();
		return ($promoCodeId) ? new \modules\promoCodes\lib\PromoCode($promoCodeId) : $this->getNoop();
	}
	
	public function getPromoCodeDiscountSum()
	{
		return round($this->getTotalPrice() / 100 * ($this->getPromoCodeDiscount()));
	}
	
	public function getPromoCodeDiscount()
	{
		return (int)$this->getPromoCode()->discount;
	}
	
	public function addDelivery($deliveryId) 
	{
		$deliveryObject = $this->getObject('\modules\deliveries\lib\Delivery', $deliveryId);
		if ($this->isNoop($deliveryObject)){
			return array('deliveryId'=>$this->getErrorsList()['deliveryId_failed']);
		}
		if ($deliveryObject->statusId != \modules\deliveries\lib\DeliveryConfig::ACTIVE_STATUS_ID){
			return array('deliveryId'=>$this->getErrorsList()['deliveryId_blocked']);
		}
		$this->addDeliveryInSession($deliveryId);
		return true;
	}
	
	public function removeDelivery()
	{
		return $this->removeDeliveryFromSession();
	}
	
	public function getDelivery()
	{
		$deliveryId = $this->getDeliveryFromSession();
		return ($deliveryId) ? new \modules\deliveries\lib\Delivery($deliveryId) : $this->getNoop();
	}
	
	public function getDeliveryPrice()
	{
		return ($this->getDelivery()->price) ? $this->getDelivery()->price : 0 ;
	}
}