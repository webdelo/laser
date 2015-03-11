<?php
namespace modules\shopcart\lib\traits;
trait ShopcartBaseMethods
{
	use \core\traits\RequestHandler,
		\core\traits\ObjectPool;

	protected $goodInterface    = 'interfaces\IGoodForShopcart';
	protected $cookieGoodsKey   = 'shopcartGoods';
	protected $sessionsGoodsKey = 'shopcartGoods';
	protected $sessionsPromoKey = 'promoCode';
	protected $sessionsDelivery = 'delivery';

	public function __construct()
	{
		$this->resetGoods();
	}

	protected function resetGoods()
	{
		$_SESSION[$this->sessionsGoodsKey] = array();
//		$this->getSESSION()[$this->sessionsGoodsKey] = array();
		return $this;
	}

	protected function checkObjectClass($objectClass)
	{
		if (class_exists($objectClass))
			return $this;
		throw new \Exception('Class "'.$objectClass.'" was not declared in the system! An exception is thrown by '.get_class($this).'.');
	}

	protected function checkObjectId($objectId)
	{
		if (empty($objectId))
			throw new \Exception('Was not passed ShopcartGood Object ID number in '.get_class($this).'.');
		return $this;
	}

	public function checkQuantityForGood($quantity, $object = null)
	{
		if (empty($quantity ) ||  !is_numeric($quantity)) {
			throw new \exceptions\ExceptionShopcart('Not specified quantity', 1);
		}
		if (is_object($object)) {
			$minGoodQuantity = $object->getMinQuantity();
			if ($quantity < $minGoodQuantity) {
				$exception = new \exceptions\ExceptionShopcart($minGoodQuantity.' - minimum amount for this product', 2);
				throw $exception->setQuantity($minGoodQuantity);
			}
		}
		return $this;
	}

	protected function getElementKeyByGood($shopcartGood)
	{
		return $this->getElementKey($shopcartGood->getObjectClass(), $shopcartGood->getObjectId());
	}

	protected function getElementKey($objectClass, $objectId)
	{
		return $objectClass.'-'.$objectId;
	}

	protected function removeShopcartGoodByKey($key)
	{
		return $this->removeGood($key)->updateCookie();
	}

	protected function getUnserializedCookie()
	{
		return unserialize($_COOKIE[$this->cookieGoodsKey]);
	}

	protected function updateCookie()
	{
		$cookieArray = array();
		foreach ($this->getGoods() as $shopcartGood) {
			$cookieArray[$this->getElementKeyByGood($shopcartGood)] = array(
				'objectClass' => $shopcartGood->getObjectClass(),
				'objectId' => $shopcartGood->getObjectId(),
				'quantity' => $shopcartGood->getQuantity(),
			);
		}
		$serializedGoodsList = serialize($cookieArray);
		setcookie($this->cookieGoodsKey, $serializedGoodsList, time()+2592000, '/');
		$_COOKIE[$this->cookieGoodsKey] = $serializedGoodsList;
		return $this;
	}

	protected function removeGood($key)
	{
		return $this->setGood($key);
	}

	protected function setGood($key, $object = null)
	{
		if (empty($object))
			unset($_SESSION[$this->sessionsGoodsKey][$key]);
		else
			$_SESSION[$this->sessionsGoodsKey][$key] = $object;
		return $this;
	}
	
	public function addPromoCodeInSession($promoId)
	{
		$_SESSION[$this->sessionsPromoKey]= $promoId;
		return $this;
	}
	
	public function removePromoCodeFromSession()
	{
		unset($_SESSION[$this->sessionsPromoKey]);
		return (!isset($_SESSION[$this->sessionsPromoKey]));
	}
	
	public function getPromoCodeFromSession()
	{
		return (isset($_SESSION[$this->sessionsPromoKey])) ? $_SESSION[$this->sessionsPromoKey] : false ;
	}

	protected function getGood($key)
	{
//		if (isset($this->getSESSION()[$this->sessionsGoodsKey][$key]))
//			return $this->getSESSION()[$this->sessionsGoodsKey][$key];
		if (isset($_SESSION[$this->sessionsGoodsKey][$key]))
			return $_SESSION[$this->sessionsGoodsKey][$key];
	}

	protected function &getGoods()
	{
//		return $this->getSESSION()[$this->sessionsGoodsKey];
		return $_SESSION[$this->sessionsGoodsKey];
	}
	
	public function addDeliveryInSession($deliveryId) 
	{
		$_SESSION[$this->sessionsDelivery]= $deliveryId;
		return $this;
	}
	
	public function removeDeliveryFromSession()
	{
		unset($_SESSION[$this->sessionsDelivery]);
		return (!isset($_SESSION[$this->sessionsDelivery]));
	}
	
	public function getDeliveryFromSession()
	{
		return (isset($_SESSION[$this->sessionsDelivery])) ? $_SESSION[$this->sessionsDelivery] : false ;
	}
}