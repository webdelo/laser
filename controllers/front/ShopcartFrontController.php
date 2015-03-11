<?php
namespace controllers\front;
class ShopcartFrontController extends \controllers\base\ShopcartBaseController
{
	use	\core\traits\controllers\Authorization,
		\core\traits\controllers\Meta,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Breadcrumbs;

	protected $shopcartClass = '\modules\shopcart\lib\AuthorizatedShopcart';
	protected $shopcart;

	protected $permissibleActions = array(
		'shopcart',
		'ajaxAddGood',
		'ajaxGetShopcartModal',
		'ajaxRemoveGood',
		'ajaxGetShopcartGoodsTableContent',
		'getShopcartGoodsTableContent',
		'ajaxChangeQuantity',
		'ajaxValidateQuantity',
		'ajaxGetTemplateContent',
		'getShopcart',
		'getTotalPrice',
		'ajaxSendOrder',
		'ajaxCheckShopcartStatusAskSaving',
		'ajaxSaveGuestShopcartToAuthorizatedShopcart',
		'ajaxDelAuthorizatedShopcartSaveGuestShopcart',
		'ajaxDelGuestShopcart',
		'ajaxAddPromoCode',
		'ajaxRemovePromoCode',
		'includeDeliveryMainTemplate',
		'getFormToDelivery',
		'ajaxRemoveDelivery',
		'saveStep2',
		'getTypeDelivery',
		
		'getShopcartBar',
		'ajaxGetShopcartBar'
	);

	public function __construct()
	{
		$this->setExecutor();
	}

	public function __call($name, $arguments)
	{
		if (empty($name))
			return $this->defaultAction();
		elseif ($this->setAction($name)->isPermissibleAction())
			return $this->callAction($arguments);
		else
			return $this->redirect404();
	}

	protected function defaultAction()
	{
		$this->shopcart();
	}

	private function setExecutor()
	{
		$shopcartClass = $this->shopcartClass;
		$this->shopcart = new $shopcartClass;
	}

	protected function shopcart()
	{
		$this->setMetaData('Оформление заказа', 'Оформление заказа', 'Оформление заказа')
			 ->setLevel('Оформление заказа')
			 ->setContent('content', $this->getShopcartGoodsTableContent())
			 ->includeTemplate('shopcart/shopcart');
	}

	private function setMetaData($title, $description, $keywords)
	{
		return $this->setTitle($title)
				->setDescription($description)
				->setKeywords($keywords);
	}

	protected function getShopcartGoodsTableContent()
	{
		ob_start();
		$this->setContent('shopcart', $this->getShopcart())
			->includeTemplate('/shopcart/goodsTable');
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
	
	public function getContent($template, $data = null)
	{
		ob_start();
		if($data)
			$this->setContent('data', $data);
		$this->includeTemplate('shopcart/'.$template);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	protected function ajaxGetTemplateContent()
	{
		$this->ajaxResponse($this->getContent($this->getPOST()['template']));
	}

	protected function ajaxAddGood()
	{
		$post = new \core\ArrayWrapper($this->getPOST());
		$res = $this->shopcart->addGood($post->objectClass, $post->objectId, $post->quantity);
		$this->ajaxResponse( $res );
	}

	protected function ajaxGetShopcartModal()
	{
		ob_start();
		$this->setContent('goodsTable', $this->getShopcartGoodsTableContent())
			->includeTemplate('/shopcart/shopcartModal');
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}

	protected function getShopcart()
	{
		return $this->shopcart;
	}

	public function getTotalPrice()
	{
		return $this->shopcart->getTotalPrice();
	}

	protected function ajaxRemoveGood()
	{
		$res = $this->shopcart->removeGoodByCode($this->getPOST()['goodCode']);
		$this->ajaxResponse($res);
	}

	protected function ajaxGetShopcartGoodsTableContent()
	{
		echo $this->getShopcartGoodsTableContent();
	}

	protected function ajaxChangeQuantity()
	{
		$res = $this->shopcart->removeGoodByCode($this->getPOST()['goodCode']);
		if($res == 1)
			$res = $this->shopcart->addGood($this->getPOST()['goodClass'], $this->getPOST()['goodId'], $this->getPOST()['quantity']);
		$this->ajaxResponse( $res );
	}

	protected function ajaxValidateQuantity()
	{
		$post = new \core\ArrayWrapper($this->getPOST());
		$shopcartObject = $this->getObject('\modules\shopcart\lib\Shopcart');
		try {
			$res = $shopcartObject->checkQuantityForGood($post->quantity, $this->getObject($post->goodClass, $post->goodId));
			$this->ajaxResponse( 1 );
		} catch (\exceptions\ExceptionShopcart $e) {
			$shopcartObject->setError('quantity_'.$post->goodId, $e->getTextError());
			$this->ajaxResponse( $shopcartObject->getErrors() );
		}

	}

	public function resetShopcart()
	{
		return $this->shopcart->resetShopcart();
	}

	private function isGoodsInShopcart($shopcart = null)
	{
		$shopcart = $shopcart ? $shopcart : $this->getShopcart();
		return $shopcart->count();
	}

	protected function ajaxCheckShopcartStatusAskSaving()
	{
		$guestShopcart = $this->getGuestShopcartObject();
		$authorizatedShopcart = $this->getAuthorizatedShopcartObject();
		if($this->isGoodsInShopcart($guestShopcart) && $this->isGoodsInShopcart($authorizatedShopcart))
			$this->ajaxResponse($this->getShowModal());
		if($this->isGoodsInShopcart($guestShopcart) && !$this->isGoodsInShopcart($authorizatedShopcart))
			$this->saveGuestShopcartToAuthorizatedShopcart();
	}

	private function getGuestShopcartObject()
	{
		return $this->getObject(get_parent_class($this))->getShopcart();
	}

	private function getAuthorizatedShopcartObject()
	{
		return $this->getObject('\modules\shopcart\lib\AuthorizatedShopcart');
	}

	private function getShowModal()
	{
		ob_start();
		$this->includeTemplate('/shopcart/askModal');
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	protected function ajaxSaveGuestShopcartToAuthorizatedShopcart()
	{
		$this->saveGuestShopcartToAuthorizatedShopcart();
	}

	private function saveGuestShopcartToAuthorizatedShopcart()
	{
		$guestShopcart = $this->getGuestShopcartObject();
		$authorizatedShopcart = $this->getAuthorizatedShopcartObject();
		foreach($guestShopcart as $good)
			$authorizatedShopcart->addGood($good->objectClass, $good->objectId, $good->quantity);
		$guestShopcart->resetShopcart();
		return false;
	}

	public function printTemplate($good, $item) {
		$this->setContent('good', $good)
			->setContent('item', $item)
			 ->includeTemplate($good->good->getPathToShopcartGoodTemplate());
	}

	protected function ajaxDelAuthorizatedShopcartSaveGuestShopcart(){
		$authorizatedShopcart = $this->getAuthorizatedShopcartObject();
		$authorizatedShopcart->resetShopcart();
		$this->saveGuestShopcartToAuthorizatedShopcart();
	}

	protected function ajaxDelGuestShopcart()
	{
		$guestShopcart = $this->getGuestShopcartObject();
		$guestShopcart->resetShopcart();
	}
	
	protected function ajaxAddPromoCode()
	{
		$this->ajaxResponse($this->shopcart->addPromoCode($this->getPOST()['promoCode']));
	}
	
	protected function ajaxRemovePromoCode()
	{
		$this->ajaxResponse($this->shopcart->removePromoCode());
	}
	
	protected function includeDeliveryMainTemplate()
	{
		$deliveries = new \modules\deliveries\lib\Deliveries();
	    $this->setContent('deliveryCategories', $deliveries->getCategories())
			 ->includeTemplate('shopcart/delivery/deliveryMainTemplate');
	}
	
	protected function getFormToDelivery()
	{
		$post = $this->getPOST();
		$deliveryId = (int)$post->deliveryId;
		if ( !empty($deliveryId) ) {
			$delivery = new \modules\deliveries\lib\Delivery($deliveryId);
			$this->setContent('delivery', $delivery)
				->includeTemplate('shopcart/delivery/formForDeliveryAdding');
		} else {
			echo '1';
		}
		
	}
	protected function ajaxRemoveDelivery()
	{
		$this->ajaxResponse($this->shopcart->removeDelivery());
	}
	
	protected function saveStep2()
	{
		$deliveryId = $this->getPOST()->deliveryId;
		
		if ( $deliveryId == '' || $deliveryId == 'null' )
			return $this->ajaxResponse(array('deliveryCategoryId'=>'Пожалуйста выберите тип доставки'));
		
		if ( $deliveryId == 'empty' || $deliveryId == '0' )
			return $this->ajaxResponse(array('deliveryId'=>'Пожалуйста выберите доставку'));
				
		if ( $this->getPOST()->flexibleAddress ) {
			if ($this->shopcart->addDelivery($this->getPOST()->deliveryId))
				return $this->getController('Clients')->ajaxSavePersonalData();
		}
		return $this->ajaxResponse($this->shopcart->addDelivery($this->getPOST()->deliveryId));
	}
	
	protected function getTypeDelivery()
	{
		$type = (string)$this->getGET()->type;
		$deliveryType = 'getTypeDelivery'.ucfirst($type);
		echo $this->$deliveryType();
	}
	
	protected function getTypeDeliveryHome()
	{
		$deliveries = new \modules\deliveries\lib\Deliveries();
		$deliveries->getCategories()->setSubquery(' AND `id` IN ( 8,10 )');
	    $this->setContent('deliveryCategories', $deliveries->getCategories())
			 ->includeTemplate('shopcart/delivery/deliveryMainTemplate');
	}
	
	protected function getTypeDeliveryPickup()
	{
		$deliveries = new \modules\deliveries\lib\Deliveries();
		$deliveries->setSubquery(' AND `categoryId` = 7 ');
		
	    $this->setContent('deliveries', $deliveries)
			 ->includeTemplate('shopcart/delivery/deliveryPickup');
	}
	
	protected function getTypeDeliveryMetro()
	{
		$deliveries = new \modules\deliveries\lib\Deliveries();
		$deliveries->setSubquery(' AND `categoryId` = 7 ');
		
	    $this->setContent('deliveries', $deliveries)
			 ->includeTemplate('shopcart/delivery/deliveryMetro');
	}
	
	protected function getShopcartBar()
	{
		$this->setContent('shopcartCount', $this->getShopcart()->getTotalQuantity())
			 ->includeTemplate('shopcart/shopcartBar');
	}

	protected function ajaxGetShopcartBar()
	{
		echo $this->getShopcartBar();
	}
	
}