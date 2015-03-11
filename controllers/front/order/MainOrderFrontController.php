<?php
namespace controllers\front\order;
class MainOrderFrontController extends \controllers\base\Controller
{
	use
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\RequestHandler;

	protected $permissibleActions = array(
		'add',
		'ajaxGetQuickOrderBlock',
		'ajaxSendQuickOrderForm',
		'getOrdersByClientIdAndArchiveStatus',
		'checkOrderAppertainToClient',
		'getOrderByNr',
		'add',
		'addOrder',
		'sendOrderByOneClick',
		'ajaxAddDeliveryToOrder',
		'ajaxAddReviewToOrder'

	);

	public function  __construct()
	{
		$this->_config = new \modules\orders\lib\OrderConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
	}

	protected function baseAddOrder($data)
	{
		$post = new \core\ArrayWrapper($data);
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($post, $this->modelObject->getConfig()->getObjectFields());
		return $objectId;
	}

	protected function addOrderGood($data)
	{
		$orderGoodConfig = new \modules\orders\orderGoods\lib\OrderGoodConfig();
		$post = new \core\ArrayWrapper($data);
		return  $this->setObject($orderGoodConfig->getObjectsClass())->modelObject->add($post, $this->modelObject->getConfig()->getObjectFields());
	}

	public function ajaxGetQuickOrderBlock()
	{
		$this->getQuickOrderBlock($this->getPOST()['goodId']);
	}

	protected function getQuickOrderBlock($goodId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);
		$this->setContent('object', $good)
			 ->includeTemplate('/order/quickOrder');
	}

	protected function printSmallParams($params)
	{
		if (is_array($params))
			$this->setContent('params', $params)
				 ->includeTemplate('catalog/smallParamsBlock');
		else
			echo $params;
		return $this;
	}

	protected function ajaxSendQuickOrderForm()
	{
		$feedback = new \modules\mailers\QuickFormFeedback($this->getPOST());
		$this->setObject($feedback)->ajax($this->modelObject->sendMail(), 'ajax');
	}

	protected function checkOrderAppertainToClient($clientId,$orderNo)
	{
		$orders = $this->getObject($this->getObjectsClass());
		
		$filters = new \core\FilterGenerator();
		$filters->setSubquery('AND `clientId` = ?d', $clientId);

		$domain = $this->getController('ModulesDomain')->getValidDomain($_SERVER['HTTP_HOST']);
		$filters->setSubquery('AND `domain` = \'?s\'', $domain);
		
		$filters->setSubquery('AND `id` = ?d',$orderNo);
		
		return $orders->setFilters($filters)->count();
	}
	
	protected function getOrdersByClientIdAndArchiveStatus($clientId, $archiveStatus=null)
	{
		$orders = $this->getObject($this->getObjectsClass());

		$filters = new \core\FilterGenerator();
		$filters->setSubquery('AND `clientId` = ?d', $clientId);

		$domain = $this->getController('ModulesDomain')->getValidDomain($_SERVER['HTTP_HOST']);
		$filters->setSubquery('AND `domain` = \'?s\'', $domain);

		$statusesArray = $archiveStatus ? 'archiveOrdersId' : 'currentOrdersId';
		$filters->setSubquery('AND `statusId` IN ('.implode(',', $this->getConfig()->$statusesArray).')');

		$filters->setOrderBy('`date` DESC, `id` DESC');

		return $orders->setFilters($filters);
	}

	private function getConfig()
	{
		return $this->_config;
	}

	private function getObjectsClass()
	{
		return $this->getConfig()->getObjectsClass();
	}

	private function getObjectClass()
	{
		return $this->getConfig()->getObjectClass();
	}

	protected function getOrderByNr($nr)
	{
		$this->setObject( $this->getObject($this->getObjectsClass()) );
		$orderId = $this->modelObject->getFieldWhereCriteria('id', $nr, 'nr', $this->getCurrentDomainAlias(), 'domain');
		return $orderId  ?  $this->getObject($this->getObjectClass(), $orderId)  :  false;
	}

	protected function add()
	{
		if( ! $this->isPostConditionsChecked() )
			return true;

		$orderId = $this->addOrder();

		if ( is_int($orderId) ) {
			$order = $this->getObject($this->objectClass, $orderId);
			if ( $this->addOrderGoods($order->id) ) {
				$order->mailNewOrderToManagers();
				$this->getController('shopcart')->resetShopcart();
				$this->ajaxResponse( array( 'result'=>$order->id, 'content'=>$this->getContent('success', $order->nr) ) );
			} else {
				$this->ajaxResponse( false );
			}
		} else {
			$this->ajaxResponse( false );
//			$this->ajax($orderId, 'ajax', true);	// for debuging in case then order could not be adding
		}
	}

	private function isPostConditionsChecked()
	{
		if( isset($this->getPost()['conditions']) )
			return true;
		else {
			$this->setError('conditions')->ajaxResponse( array( 'result' => false, 'content'=> $this->getErrors() ) );
			return false;
		}
	}

	protected function addOrder()
	{
		$formData = array();
		$config   = $this->_config;
		$userData = $this->getAuthorizatedUser();
		$shopcart = $this->getController('shopcart')->getShopcart();

		$formData['paymentStatusId'] = $config::START_PAYMENT_STATUS_ID;
		$formData['statusId']     = $config::NEW_ORDER_STATUS_ID;
		$formData['categoryId']   = $config::FROM_SITE_CATEGORY_ID;
		$formData['rate']         = $this->getObject('\core\Settings')->getAllGlobalSettings()->rate;
		$formData['moduleId']     = $this->getObject('\modules\modulesDomain\lib\ModuleDomainConfig')->devicesId;
		$formData['domain']       = $this->getCurrentDomainAlias();
		$formData['clientId']     = $userData->id;
		$formData['country']      = $userData->deliveryCountry ? $userData->deliveryCountry : '---';
		$formData['index']        = $userData->deliveryIndex;
		$formData['region']       = $userData->deliveryRegion;
		$formData['city']         = $userData->deliveryCity ? $userData->deliveryCity : '---';
		$formData['street']       = $userData->deliveryStreet ? $userData->deliveryStreet : '---';
		$formData['home']         = $userData->deliveryHome ? $userData->deliveryHome : '---';
		$formData['block']        = $userData->deliveryBlock;
		$formData['flat']         = $userData->deliveryFlat;
		$formData['person']       = $userData->deliveryPerson;
		$formData['phone']        = $userData->phone;
		$formData['cashRate']     = 0;
		$formData['deadline']     = null;
		$formData['promoCodeId']       = $shopcart->getPromoCode()->id;
		$formData['promoCodeDiscount'] = $shopcart->getPromoCode()->discount;
		$formData['deliveryId']        = $shopcart->getDelivery()->id;
		$formData['deliveryPrice']     = $shopcart->getDelivery()->price;
		$formData['type']     = 'fromClient';
		$formData['cashPayment'] = $this->getPOST()['cashPayment'];		
		$formData['addedBy']     = $userData->getLogin();

		return $this->baseAddOrder($formData);
	}

	private function addOrderGoods($orderId)
	{
		foreach($this->getController('shopcart')->getShopcart() as $good){
			$goodData['orderId'] = $orderId;
			$goodData['goodId'] = $good->id;
			$goodData['quantity'] = $good->quantity;
			$goodData['price'] = $good->getPrice();
			$goodData['basePrice'] = is_numeric($good->good->getBasePriceByMinQuantity()) ? $good->good->getBasePriceByMinQuantity() : 0.0000001;

			if(!is_int($this->addOrderGood($goodData)))
				return false;
		}
		return true;
	}

	protected function sendOrderByOneClick ()
	{
		$post = $this->getPOST();
		if ( $post->phoneNumber ) {
			if (!strripos($post->phoneNumber, '_') ) {
				$newOrderByOneClickMail = new \modules\mailers\OrderByOneClickMail();
				$result = $newOrderByOneClickMail->sendPhoneNumberToManagers($post->goodId, $post->phoneNumber);
			} else {
				$result = array( 'phoneNumber' => 'Вы ввели неверный номер телефона, попытайтесь пожалуйста еще раз' );
			}
		} else {
			$result = array( 'phoneNumber' => 'Пожалуйста введите свой номер телефона' );
		}
		$this->ajaxResponse($result);
	}
	
	public function ajaxAddDeliveryToOrder()
	{
		$deliveryId = (new \modules\deliveries\lib\DeliveryConfig())->getStandartPriceDeliveryId();
		$deliveryObject = (new \modules\deliveries\lib\Delivery($deliveryId)); 
		$deliveryPrice = $deliveryObject->getPrice();
		$_POST['deliveryPrice'] = $deliveryPrice;
		$this->setObject('\modules\orders\lib\Order', $this->getPOST()['orderId'])
			 ->ajax($this->modelObject->addDelivery($this->getPOST()));
	}

	public function ajaxAddReviewToOrder()
	{
		$this->setObject('\modules\orders\lib\Order', $this->getPOST()['orderId']);
		$result = $this->modelObject->addReview($this->getPOST());		
		if ($result) {
			//$this->ajaxResponse(array('result'=>'ok'));
			$result = array('result'=>'ok');
		} else {
			$result = array( 'reviewMessage' => 'Пожалуйста введите текст отзыва' );
		}
		$this->ajaxResponse($result);
	}
	
	private function getContent($template, $data = null)
	{
		if($data)
			$this->setContent('data', $data);
		ob_start();
		$this->includeTemplate('/order/'.$template);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
