<?php
namespace controllers\admin;
class OrdersAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\RequestHandler,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\core\traits\controllers\Files,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const MANAGER_USER_CLASS  = '\modules\managers\lib\Manager';

	protected $permissibleActions = array(
		'orders',
		'order',
		'orderEditMode',
		'add',
		'edit',
		'ajaxGroupEdit',
		'editOrder',
		'editPaidPercent',
		'editDescription',
		'editDriverNotice',
		'remove',
		'getTemplateToAlertPartner',
		'getTemplateToMessagePartner',
		'ajaxAlertPartner',
		'ajaxMessagePartner',
		'ajaxGetOrderHistory',
		'getOrderGoodDetails',
		'getActiveManagers',
		'getActivePartners',
		'getClientById',
		'getOrderGoodById',
		'editOrderGoodDetails',
		'ajaxEditOrderGoodDetails',
		'getOrdersQuantityByStatusId',
		'cash',
		'ajaxAddPromoCodeToOrder',
		'ajaxRemovedPromoCodeFromOrder',
		'ajaxAddDeliveryToOrder',
		'ajaxRemoveDeliveryFromOrder',
		'ajaxGetDeliveryTemplate',
		'getDeliveryTemplate',
		'changePartner',
		'getOrderGoodsTable',
		'ajaxGetOrderGoodsTable',
		'getOrderGoodForm',
		'ajaxGetOrderGoodForm',
		'groupProfit',
		'mailGroupProfit',
		'charts',
		'groupDriver',
		'mailGroupDriver',
		'ajaxEditPropertyRelation',
		'editPropertyRelation',
		'isHasParametersOrProperties',
		'ajaxGetModalChangeStatusId',
		'ajaxSendChangeStatusIdMessages',
		'ajaxEditPaidPercentGroup',
		'editOrderComplectsGoodDetals',
		'editOrderComplectsGoodDetalsDo',
		'ajaxEditComplectsPropertyRelation',
		'editComplectsPropertyRelation',
		'getPartnerChangeStatusBlock',
		'ajaxPartnerEditOrderStatusId',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

		// Start: Categories Trait Methods
		'categories',
		'categoryAdd',
		'categoryEdit',
		'category',
		'removeCategory',
		'getMainCategories',
		'changeCategoriesPriority',
		//   End: Categories Trait Methods

		/* Start: Files Trait Methods*/
		'uploadFile',
		'addFilesFromEditPage',
		'removeFile',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editFile',
		'getTemplateToEditFile',
		'ajaxGetFilesBlock',
		'ajaxGetFilesListBlock',
		'getFileIcon',
		'download',
		'ajaxManagerConfirmOrder',
		/*   End: Files Trait Methods*/
	);

	protected $permissibleActionsForManagersUsers = array(
		'orders',
		'order',
		'viewOrder',
		'edit',
		'getTemplateToAlertPartner',
		'getTemplateToMessagePartner',
		'getActiveManagers',
		'getActivePartners',
		'getClientById',
		'getOrderGoodById',
		'getOrdersQuantityByStatusId',
		'cash',
		'ajaxGetOrderHistory',
		/* Start: Files Trait Methods*/
		'uploadFile',
		'addFilesFromEditPage',
		'ajaxGetFilesBlock',
		'ajaxGetFilesListBlock',
		'getFileIcon',
		'download',
		'ajaxManagerConfirmOrder',
		'ajaxPartnerEditOrderStatusId',
		/*   End: Files Trait Methods*/
	);

	protected $permissibleEditFieldsForPartnerManager = array(
		'statusId',
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\orders\lib\OrderConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();

		if($this->isAuthorisatedUserAnManager())
			$this->permissibleActions = $this->permissibleActionsForManagersUsers;
	}

	protected function defaultAction()
	{
		return $this->orders();
	}

	protected function orders()
	{
		$this->checkUserRightAndBlock('orders');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);

		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$categoryId = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$nr = (empty($this->getGET()['nr'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['nr']);
		$invoiceNr = (empty($this->getGET()['invoiceNr'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['invoiceNr']);
		$paymentOrderNr = (empty($this->getGET()['paymentOrderNr'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['paymentOrderNr']);
		$partnerConfirmed = (isset($this->getGET()['partnerConfirmed'])) ? '1' : 0;
		$paidPercent = (isset($this->getGET()['paidPercent'])) ? '1' : 0;
		$city = (empty($this->getGET()['city'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['city']);
		$street = (empty($this->getGET()['street'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['street']);
		$home = (empty($this->getGET()['home'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['home']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);
		$manager = (empty($this->getGET()['managerId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['managerId']);
		$partner = (empty($this->getGET()['partnerId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['partnerId']);
		$clientId = (empty($this->getGET()['clientId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['clientId']);
		$goodId = (empty($this->getGET()['goodId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['goodId']);
		$moduleId = (empty($this->getGET()['moduleId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['moduleId']);
		$domain = (empty($this->getGET()['domain'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['domain']);
		$invoiceNrDateStart = (empty($this->getGET()['invoiceNrDateStart'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['invoiceNrDateStart']);
		$invoiceNrDateEnd = (empty($this->getGET()['invoiceNrDateEnd'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['invoiceNrDateEnd']);
		$paymentOrderNrDateStart = (empty($this->getGET()['paymentOrderNrDateStart'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['paymentOrderNrDateStart']);
		$paymentOrderNrDateEnd = (empty($this->getGET()['paymentOrderNrDateEnd'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['paymentOrderNrDateEnd']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($categoryId))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $categoryId);


		if(!empty($this->getGET()['paymentStatuses'])){
			if (implode(',', $this->getGET()['paymentStatuses']))
				$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `paymentStatusId` IN ('.implode(',', $this->getGET()['paymentStatuses']).')');
		}

		if(!empty($this->getGET()['statuses'])){
			if (implode(',', $this->getGET()['statuses']))
				$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` IN ('.implode(',', $this->getGET()['statuses']).')');
		}
		else{
			$config = $this->_config;
			$this->modelObject->setSubquery('AND `statusId` NOT IN (?d, ?d)', $config::CANCELED_STATUS_ID, $config::REMOVED_STATUS_ID);
			$this->modelObject->setSubquery(
				' AND ( `statusId` != ?d OR `paymentStatusId` !=?d OR `paidPercent` != ?d ) ',
				$config::COMPLETED_ORDER_STATUS_ID,
				$config::PAID_ORDER_STATUS_ID,
				1
			);
		}

		if (!empty($nr))
			$this->modelObject->setSubquery('AND `nr` LIKE \'%?s%\'', $nr);

		if (!empty($invoiceNr))
			$this->modelObject->setSubquery('AND `invoiceNr` LIKE \'%?s%\'', $invoiceNr);

		if (!empty($paymentOrderNr))
			$this->modelObject->setSubquery('AND `paymentOrderNr` LIKE \'%?s%\'', $paymentOrderNr);

		if (!empty($partnerConfirmed))
			$this->modelObject->setSubquery('AND `partnerConfirmed` = ?d', $partnerConfirmed);

		if (!empty($paidPercent))
			$this->modelObject->setSubquery('AND `paidPercent` = ?d', $paidPercent);

		if (!empty($city))
			$this->modelObject->setSubquery('AND `city` LIKE \'%?s%\'', $city);
		if (!empty($street))
			$this->modelObject->setSubquery('AND `street` LIKE \'%?s%\'', $street);

		if (!empty($home))
			$this->modelObject->setSubquery('AND `home` LIKE \'%?s%\'', $home);


		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($manager))
			$this->modelObject->setSubquery('AND `managerId` = ?d', $manager);

		if (!empty($partner))
			$this->modelObject->setSubquery('AND `partnerId` = ?d', $partner);

		if (!empty($clientId)){
			$this->modelObject->setSubquery('AND `clientId` = ?d', $clientId);
		}

		if (!empty($goodId)){
			$this->modelObject->setSubquery('AND `id` IN (SELECT `orderId`  FROM `'.$this->getObject('\modules\orders\orderGoods\lib\OrderGoods')->mainTable().'` WHERE `goodId` = ?d)', $goodId);
		}

		if (!empty($moduleId)){
			$this->modelObject->setSubquery('AND `moduleId` = ?d', $moduleId);
		}

		if (!empty($domain)){
			$this->modelObject->setSubquery('AND `domain` = \'?s\'', $domain);
		}

		if (!empty($invoiceNrDateStart))
			$this->modelObject->setSubquery('AND `invoiceNrDate` >= ?d', \core\utils\Dates::convertDate($invoiceNrDateStart));

		if (!empty($invoiceNrDateEnd))
			$this->modelObject->setSubquery('AND `invoiceNrDate` <= ?d', \core\utils\Dates::convertDate($invoiceNrDateEnd));

		if (!empty($paymentOrderNrDateStart))
			$this->modelObject->setSubquery('AND `paymentOrderNrDate` >= ?d', \core\utils\Dates::convertDate($paymentOrderNrDateStart));

		if (!empty($paymentOrderNrDateEnd))
			$this->modelObject->setSubquery('AND `paymentOrderNrDate` <= ?d', \core\utils\Dates::convertDate($paymentOrderNrDateEnd));



		if($this->isAuthorisatedUserAnManager()){
			$this->modelObject->setSubquery('AND `partnerId` = ?d', $this->getAuthorizatedUser()->partnerId);
		}

		$this->modelObject->setOrderBy('`date` DESC, `id` DESC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'orders'.$this->getOrderTemplateNameExtention());
	}

//	protected function add()
//	{
//		$this->checkUserRightAndBlock('order_add');
//		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
//		if ($objectId){
//			$this->setObject($this->_config->getObjectClass(), $objectId);
//			$this->getObject($this->_config->getObjectClass(), $objectId)->mailNewOrderToManagers();
//		}
//		$this->ajax($objectId, 'ajax', true);
//	}

	protected function edit($data = null)
	{
		$data = $data ? $data : $this->getPOST();
		$filteredPost = $this->isAuthorisatedUserAnManager() ? $this->filterPostFields() : $this->getPOST();
		$this->checkUserRightAndBlock('order_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($filteredPost, $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function ajaxGroupEdit()
	{
		foreach ($this->getPost()['group'] as $id){
			if(!$this->getObject($this->objectClass, $id)->edit(array($this->getPost()['field']=>$this->getPost()['value'])))
				return $this->ajaxResponse('Error while trying to change group properties!');
		}
		$this->ajaxResponse(1);
	}

	protected function changePartner()
	{
		if (!$this->getPOST()->partnerId) {
			$this->ajaxResponse(1);
			return;
		}
		$this->checkUserRightAndBlock('order_edit');
		$partner = $this->getObject('\modules\partners\lib\Partner', (int)$this->getPOST()->partnerId);
		$order = $this->getObject($this->objectClass, (int)$this->getREQUEST()[0]);
		$data = array(
			'partnerId' => $partner->id,
			'cashRate'  => $partner->cashRate,
			'partnerNotifyHistory' => '',
			'partnerNotified' => 0,
			'partnerConfirmed' =>0
		);

		if ( $order->edit($data) ) {
			$this->updateBasePrices($order);
			$this->ajaxResponse((int)$partner->cashRate);
		}
		else
			$this->ajaxResponse(array('partnerId'=>'Не удалось сменить партнера'));
	}

	private function updateBasePrices($order)
	{
		foreach($order->getOrderGoods() as $good){
			$res = \core\db\Db::getMysql()->query(
				'UPDATE `'.$this->getObject('\modules\orders\orderGoods\lib\OrderGoodConfig')->mainTable().'`
				SET `basePrice`=\''.$good->getGood()->getBasePriceByQuantityForPartner($good->getQuantity(), $order->partnerId).'\'
				WHERE id='.$good->id
			);
			if(!$res)
				throw new Exception('Error while trying to update base prices after changing partner in order!');
		}
	}

	protected function updateCashRate($orderId, $cashRate)
	{
		$order = $this->getObject($this->objectClass, $orderId);
		return $order->edit(array('cashRate'=>$cashRate));
	}

	public function ajaxUpdateCashRate()
	{
		$this->ajaxResponse($this->getGET()->orderId, $this->getPOST()->cashRate);
	}

	private function filterPostFields()
	{
		$post = array();
		$order = $this->getObject($this->_config->getObjectClass(), (int)$this->getPOST()['id']);
		foreach($this->_config->getObjectFields() as $key=>$value)
			if( in_array($key, $this->permissibleEditFieldsForPartnerManager))
				$post[$key] = $this->getPost()[$key];
			else
				$post[$key] = $order->$key;
		return $post;
	}

	protected function order()
	{
		$this->checkUserRightAndBlock('order');
		$this->useRememberPastPageList();

		$order = new \core\Noop();
		if (isset($this->getREQUEST()[0])){
			$order = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
			if($this->isAuthorisatedUserAnManager())
				$this->checkPartnerRightForOrder($order->partnerId);
		}

		$tabs = array(
			'viewOrderDetails' => 'Основные данные',
		);

		$order->id ? $tabs = array_merge($tabs, array('files' => 'Файлы')) : '';

		$orders = new $this->objectsClass;
		$settings = new \core\Settings();
		$settings = $settings->getSettings('*', array('where' => array('query' => 'type="'.TYPE.'" OR type="all"')));

		$this->setContent('order', $order)
			->setContent('tabs', $tabs)
			->setContent('orders', $orders)
			->setContent('object', $order) // Need for files template
			->setContent('objects', $orders) // Need for files template
			->setContent('statuses', $orders->getStatuses())
			->setContent('mainCategories', $orders->getMainCategories(1))
			->setContent('activePartners', $this->getActivePartners())
			->setContent('activeManagers', $this->getActiveManagers())
			->setContent('client', $this->getClientById($order->clientId))
			->setContent('settingsRate', $settings['rate'])
			->includeTemplate('viewOrder');
	}

	protected function editOrder()
	{
		$this->checkUserRightAndBlock('order_edit');
		$order = $this->getObject($this->objectClass, $this->getGET()->orderId);
		$edit = $order->edit($this->getPOST());
		if(is_int($edit)  &&  $this->isPartnerConfirmedEdit())
			$this->addNotifyConfirmToHistory(new $this->objectClass($this->getGET()->orderId), $this->getPost()['partnerConfirmed']);
		$this->ajaxResponse($edit);
	}

	private function isPartnerConfirmedEdit()
	{
		if($_POST === array('partnerConfirmed'=>'1'))
			return true;
		if($_POST === array('partnerConfirmed'=>'0'))
			return true;
		return false;
	}

	protected function editDescription()
	{
		$this->checkUserRightAndBlock('order_edit');
		$order = $this->getObject($this->objectClass, $this->getGET()->orderId);
		if ( str_replace(' ', '', $this->getPOST()->description) !== str_replace(' ', '', $order->description) ) {
			$descriptionNotice = new \modules\mailers\EditDescriptionNotice($order);
			$descriptionNotice->sendMail($this->getPOST()->description);
		}
		$this->ajaxResponse($order->editField($this->getPOST()->description, 'description'));
	}

	protected function editDriverNotice()
	{
		$this->checkUserRightAndBlock('order_edit');
		$order = $this->getObject($this->objectClass, $this->getGET()->orderId);
		$this->ajaxResponse($order->editField($this->getPOST()->driverNotice, 'driverNotice'));
	}

	protected function cash()
	{
		$this->checkUserRightAndBlock('order');

		$order = new \core\Noop();
		if (isset($this->getREQUEST()[0])){
			$order = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
			if($this->isAuthorisatedUserAnManager())
				$this->checkPartnerRightForOrder($order->partnerId);
		}

		$this->setContent('order', $order)
			 ->setContent('client', $this->getClientById($order->clientId))
			 ->setContent('orderGoods', $order->getOrderGoods())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'cash');
	}

	private function checkPartnerRightForOrder($partnerId)
	{
		$authorizatedManager = $this->getAuthorizatedManager();
		if( $authorizatedManager->isManagerBelongsToPartner($partnerId) )
			return $this;
		else{
			echo 'Access denied';
			throw new \exceptions\ExceptionAccess();
		}
	}

	private function getAuthorizatedManager()
	{
		return $this->getObject(self::MANAGER_USER_CLASS, $this->getAuthorizatedUser()->id);
	}

	protected function getActiveManagers()
	{
		$administrators = $this->getObject('\modules\administrators\lib\Administrators');
		return $administrators->getActiveManagers();
	}

	protected function getActivePartners()
	{
		$partners = $this->getObject('\modules\partners\lib\Partners');
		return $partners->getActivePartners();
	}

	protected function getClientById($clientId)
	{
		return $clientId ? $this->getObject('\modules\clients\lib\Client', $clientId) : $this->getNoop();
	}

	private function getOrderTemplateNameExtention()
	{
		return $this->isAuthorisatedUserAnManager() ? 'Manager' : '';
	}

	protected function getOrderGoodById($goodId)
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('order_delete');
		if (isset($this->getREQUEST()[0]))
			$orderId = (int)$this->getREQUEST()[0];
		if (!empty($orderId)) {
			$order = $this->getObject($this->objectClass, $orderId);
			$this->ajaxResponse($order->remove());
		}
	}

	protected function getTemplateToMessagePartner()
	{
		$this->checkUserRightAndBlock('order_getPartnerNotifyTemplate');
		$order = $this->getObject($this->objectClass, (int)$_REQUEST[0]);
		if(!$order->partnerId){
			echo '<p class="noPartnerId">У заказа не выбран партнер. Отправка невозможна.</p>';
			return true;
		}

		$settings = new \core\Settings();
		$this->setContent('order', $order)
			->setContent('time', date("d.m.y").'-'.date("H:i:s"))
			->setContent('adminEmail', $settings->getAllGlobalSettings()['admin_email'])
			->includeTemplate($this->_config->getAdminTemplateDir().'./mailTemplates/messagePartnerTemplate');
	}

	protected function ajaxMessagePartner()
	{
		if(!isset($this->getPost()['managers']))
			return $this->ajax(array('res'=>'error', 'message'=>'<span style="color: red; font-size: 16px;">Вы не выбрали ни одного менеджера. Сообщение не было отправлено.</span>', 'ajax'));

		$this->checkUserRightAndBlock('order_partnerNotifySend');
		$order = $this->getObject($this->objectClass, (int)$this->getPOST()['orderId']);
		$alert = new \modules\mailers\AlertPartner($order);
		$res = $alert->sendMessagePartner();
		if($res == 1)
			$this->addToHistory($order, 'messagePartnerBase');
		else
			$this->ajax(array('res'=>'error', 'message'=>'Произошла ошибка при попытке отправить оповещение партнеру. Обратитесь к разработчикам.', 'ajax'));
	}

	protected function getTemplateToAlertPartner()
	{
		$this->checkUserRightAndBlock('order_getPartnerNotifyTemplate');
		$order = $this->getObject($this->objectClass, (int)$_REQUEST[0]);
		if(!$order->partnerId){
			echo '<p class="noPartnerId">У заказа не выбран партнер. Отправка невозможна.</p>';
			return true;
		}

		$settings = new \core\Settings();
		$this->setContent('order', $order)
			->setContent('time', date("d.m.y").'-'.date("H:i:s"))
			->setContent('adminEmail', $settings->getAllGlobalSettings()['admin_email'])
			->includeTemplate($this->_config->getAdminTemplateDir().'./mailTemplates/alertPartnerTemplate');
	}

	protected function ajaxAlertPartner()
	{
		if(!isset($this->getPost()['managers']))
			return $this->ajax(array('res'=>'error', 'message'=>'<span style="color: red; font-size: 16px;">Вы не выбрали ни одного менеджера. Сообщение не было отправлено.</span>', 'ajax'));

		$this->checkUserRightAndBlock('order_partnerNotifySend');
		$order = $this->getObject($this->objectClass, (int)$this->getPOST()['orderId']);
		$alert = new \modules\mailers\AlertPartner($order);
		$res = $alert->sendAlertPartner();
		if($res == 1)
			$this->addToHistory($order, 'alertPartnerMail');
		else
			$this->ajax(array('res'=>'error', 'message'=>'Произошла ошибка при попытке отправить оповещение партнеру. Обратитесь к разработчикам.', 'ajax'));
	}

	private function addToHistory($order, $bodyFromFile)
	{
		$contentToAddToHistory = $this->getContentToAddToHistory($order, $bodyFromFile);
		$order->partnerNotifyHistory = $contentToAddToHistory.$order->partnerNotifyHistory;
		$order->lastNotify = $this->getPost()['time'];
		$order->partnerNotified = 1;

		$res = $order->edit();

		if(is_int($res))
			$this->ajax(array('res'=>'ok', 'message'=>'Сообщение было успешно отправлено. История оповещений обновлена.', 'ajax'), 'ajax');
		else
			$this->ajax(array('res'=>'error', 'message'=>'Произошла ошибка при попытке изменения истории оповещения партнера после отправки оповещения. Обратитесь к разработчикам.', 'ajax'));
	}

	private function getContentToAddToHistory($order, $bodyFromFile)
	{
		$alert = new \modules\mailers\AlertPartner($order);
		$contents = $alert->getAlertPartnerContent($bodyFromFile);
		$settings = new \core\Settings();

		$managers = $this->getPost()['copyToAdmin'] == 'true' ? $settings->getAllGlobalSettings()['admin_email'].', ' : '';
		foreach($this->getPost()['managers'] as $manager )
			$managers .= $manager.', ';
		$managers = substr($managers, 0, strlen($managers) - 2);

		return '<br /><hr align="center" width="80%" size="2" color="black" /><br /><b>'.$this->getPost()['time'].'</b><br /> отправлено: '
						.$order->getPartner()->name.' ( '.$managers.' )<br />'
						.$contents;
	}

	private function addNotifyConfirmToHistory($order, $partnerConfirmed)
	{
		$contentToAddToHistory = '<br /><hr align="center" width="80%" size="2" color="blue" /><br /><b>'.date("d.m.y").'-'.date("H:i:s").'</b>
			<br /> Пользователь <strong>'.$this->getAuthorizatedUser()->getLogin().($partnerConfirmed ? ' подтвердил' : ' отменил').'</strong> получение оповещение<br />';
		$order->edit(array('partnerNotifyHistory'=>$contentToAddToHistory.$order->partnerNotifyHistory));
	}

	protected function ajaxGetOrderHistory()
	{
		echo $this->getOrderHistory($this->getRequest()[0]);
	}

	private function getOrderHistory($id)
	{
		return $this->getObject($this->objectClass, $id)->partnerNotifyHistory;
	}

	protected function getOrdersQuantityByStatusId($statusId)
	{
		if( ! $this->checkUserRight('orders'))
			return false;

		$orders = new $this->objectsClass;

		$where['query'] = 'mt.statusId=?d';
		$where['data'] = array($statusId);

		if($this->isAuthorisatedUserAnManager()){
			$partner = $this->getAuthorizatedManagerPartner();
			$where['query'] .= ' AND mt.partnerId=?d';

			$where['data'][] = $partner->id;
		}

		$filter = array('where' => $where);
		return $orders->countAll($filter);
	}

	private function getAuthorizatedManagerPartner()
	{
		$authorizatedManager = $this->getAuthorizatedManager();
		return $this->getObject('\modules\partners\lib\Partner', $authorizatedManager->partnerId);
	}

	public function printGoodTemplate($good, $i)
	{
		$this->setContent('good', $good)
			 ->setContent('i', $i)
			 ->includeTemplate($good->getGood()->getPathToAdminOrderGoodTemplate());
	}

	protected function ajaxAddPromoCodeToOrder()
	{
		$this->setObject('\modules\orders\lib\Order', $this->getPOST()['orderId'])
			 ->ajax($this->modelObject->addPromoCode($this->getPOST()['promoCode']));
	}

	protected function ajaxRemovedPromoCodeFromOrder()
	{
		$this->setObject('\modules\orders\lib\Order', $this->getPOST()['orderId'])
			 ->ajax($this->modelObject->removePromoCode());
	}

	protected function includeDeliveryAddingTemplate($order)
	{
		$deliveries = new \modules\deliveries\lib\Deliveries();
		$categories = $deliveries->getCategories();
		if ($order->domain)
			$categories->setSubquery(' AND `parentId` = ( SELECT `id` FROM `'.$categories->mainTable().'` WHERE `alias` = \'?s\' ) ', $order->domain);

		$this->setContent('deliveryCategories', $categories)
			->setContent('order', $order)
			->includeTemplate('delivery/deliveryAdding');
	}

	public function getFormToDelivery()
	{
		echo $this->setContent('order', $this->getNoop())->getFormByDeliveryId((int)$this->getPOST()->deliveryId);
	}

	public function getFormByDeliveryId($id)
	{
		if (empty($id))
			return $this->ajaxResponse(array('deliveryId'=>'Выберите пожалуйста вид доставки'));
		$this->setContent('delivery', new \modules\deliveries\lib\Delivery($id))
			 ->includeTemplate('delivery/formForDeliveryAdding');
	}

	public function ajaxAddDeliveryToOrder()
	{
		$this->setObject('\modules\orders\lib\Order', $this->getPOST()['orderId'])
			 ->ajax($this->modelObject->addDelivery($this->getPOST()));
	}

	public function ajaxRemoveDeliveryFromOrder()
	{
		$this->setObject('\modules\orders\lib\Order', $this->getREQUEST()[0])
			 ->ajax($this->modelObject->removeDelivery());
	}

	public function getOrderGoodsTable($orderId)
	{
		$order = $this->getObject($this->objectClass, $orderId);
		$this->setContent('order', $order)
			 ->includeTemplate('viewOrderGoods');
	}

	public function ajaxGetOrderGoodsTable()
	{
		echo $this->getOrderGoodsTable($this->getGET()->orderId);
	}

	public function getOrderGoodForm($orderId)
	{
		$this->includeTemplate('orderGoodForm');
	}

	public function ajaxGetOrderGoodForm()
	{
		echo $this->getOrderGoodForm($this->getGET()->orderId);
	}

	protected function groupProfit($idString = '')
	{
		$this->checkUserRightAndBlock('order_groupProfit');

		$idString = $idString ? $idString : $this->getGet()['ids'];
		if(!is_string($idString))
			throw new Exception ( 'Invalid orders id string in '.get_class($this).'!' );

		$this->getProfitGenerator($idString)->displayProfitTable();
	}

	private function getProfitGenerator($idString)
	{
		return new \modules\orders\lib\ProfitGenerator( $this->getObject($this->objectsClass)->getOrdersByIds($idString) );
	}

	protected function mailGroupProfit()
	{
		$data = $_POST;
		$data['table'] = $this->getProfitGenerator($this->getPost()['ids'])->getProfitTable();
		$data['domain'] = $this->getObject($this->objectsClass)->getOrdersByIds($this->getPost()['ids'])->getFirst()->domain;
		$mailGroupProfit = new \modules\mailers\MailGroupProfit($data);
		$res = $this->setObject($mailGroupProfit)->modelObject->sendGroupProfit();
		$this->ajax($res, 'ajax');
	}

	protected function charts()
	{
		$this->checkUserRightAndBlock('charts');
		$this->includeTemplate('/reports/charts');
	}

	protected function ajaxGetDeliveryTemplate()
	{
		echo $this->getDeliveryTemplate((int)$_REQUEST[0]);
	}

	protected function getDeliveryTemplate($orderId)
	{
		$this->setContent('order', new $this->objectClass($orderId))->includeTemplate('delivery/mainDeliveryTemplate');
	}

	protected function groupDriver()
	{
		$this->checkUserRightAndBlock('order_groupDriver');
		$this->setContent('table', $this->getGroupDriverContent($this->getGet()['ids']))
			->includeTemplate('/reports/groupDriver');
	}

	protected function  getGroupDriverContent($idString)
	{
		$this->checkUserRightAndBlock('order_groupDriver');
		$objects = $this->getObject($this->objectsClass)->getOrdersByIds($idString);
		ob_start();
		$this->setContent('objects', $objects)
			->includeTemplate('/reports/groupDriverContent');
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	protected function mailGroupDriver()
	{
		$this->checkUserRightAndBlock('order_groupDriver');
		$data = $_POST;
		$data['table'] = $this->getGroupDriverContent($data['ids']);
		$data['domain'] = $this->getObject($this->objectsClass)->getOrdersByIds($this->getPost()['ids'])->getFirst()->domain;
		$mailGroupDriver = new \modules\mailers\MailGroupDriver($data);
		$res = $this->setObject($mailGroupDriver)->modelObject->sendGroupDriver();
		$this->ajax($res, 'ajax');
	}

	protected function getOrderGoodDetails()
	{
		$orderGood = $this->getObject('\modules\orders\orderGoods\lib\OrderGood', $this->getGET()->objectId);

		if($orderGood->isAnComplect())
			return $this->getComplectDetails($orderGood);

		$this->setContent('orderGood', $orderGood)
			->setContent('parameters', $this->getParametersByCategoryAlias($orderGood))
			->setContent('properties', $this->getPropertiesByCategoryAlias($orderGood))
			->includeTemplate('orderGoodDetails');
	}

	private function getComplectDetails($orderGood)
	{
		$orderGoods = $this->getObject('\modules\orders\orderComplectsGoods\lib\OrderComplectsGoods')
						->setSubquery(' AND `parentId` = ?d', $orderGood->id);

		$this->includeTemplate('orderGoodsDetailsHeader');

		foreach($orderGoods as $orderGood){
			$this->setContent('orderGood', $orderGood)
				 ->setContent('parameters', $this->getParametersByCategoryAlias($orderGood))
				 ->setContent('properties', $this->getPropertiesByCategoryAlias($orderGood))
				 ->includeTemplate('orderComplectsGoodDetails');
			echo '<br /><br /><br /><hr>';
		}

	}

	private function getParametersByCategoryAlias($orderGood)
	{
		if($orderGood->isAnOffer())
			$orderGood = $orderGood->getGood();
		if($orderGood->isAnComplect())
			$orderGood = $orderGood->getGood()->getPrimaryGood();

		$parameters = new \modules\parameters\lib\Parameters;
		$parameters->setSubquery(' AND ( `id` IN ( SELECT `ownerId` FROM `'.$parameters->mainTable().'_additional_categories` WHERE `objectId` IN (SELECT `id` FROM `'.$parameters->mainTable().'_categories` WHERE `alias`=\'?s\' )) OR `id` IN ( SELECT `ownerId` FROM `'.$parameters->mainTable().'_additional_categories` WHERE `objectId` IN (SELECT `id` FROM `'.$parameters->mainTable().'_categories` WHERE `alias`=\'?s\' )) )', $orderGood->getGood()->getCategory()->getParent()->alias, $orderGood->getGood()->getCategory()->alias);
		return $parameters;
	}

	private function getPropertiesByCategoryAlias($orderGood)
	{
		if($orderGood->isAnOffer())
			$orderGood = $orderGood->getGood();
		if($orderGood->isAnComplect())
			$orderGood = $orderGood->getGood()->getPrimaryGood();

		$properties = new \modules\properties\lib\Properties;
		$properties->setSubquery(' AND ( `id` IN ( SELECT `ownerId` FROM `'.$properties->mainTable().'_additional_categories` WHERE `objectId` IN (SELECT `id` FROM `'.$properties->mainTable().'_categories` WHERE `alias`=\'?s\' )) OR `id` IN ( SELECT `ownerId` FROM `'.$properties->mainTable().'_additional_categories` WHERE `objectId` IN (SELECT `id` FROM `'.$properties->mainTable().'_categories` WHERE `alias`=\'?s\' )) )', $orderGood->getGood()->getCategory()->getParent()->alias, $orderGood->getGood()->getCategory()->alias);
		return $properties;
	}

	protected function ajaxEditOrderGoodDetails()
	{
		$this->ajaxResponse($this->editOrderGoodDetails($this->getPost()));
	}

	protected function editOrderGoodDetails($post)
	{
		$orderGood = $this->getObject('\modules\orders\orderGoods\lib\OrderGood', $post->objectId);
		return $orderGood->getParameters()->edit($post->parameters);
	}

	protected function editOrderComplectsGoodDetals($post = null)
	{
		$this->ajaxResponse($this->editOrderComplectsGoodDetalsDo( isset($post) ? $post : $this->getPost() ));
	}

	protected function editOrderComplectsGoodDetalsDo($post)
	{
		$orderGood = $this->getObject('\modules\orders\orderComplectsGoods\lib\OrderComplectsGood', $post->objectId);
		return $orderGood->getParameters()->edit($post->parameters);
	}

	protected function ajaxEditPropertyRelation () {
		$this->ajaxResponse($this->editPropertyRelation( $this->getPOST() ));
	}

	protected function editPropertyRelation ($post)
	{
		$object = $this->getObject('\modules\orders\orderGoods\lib\OrderGood', $post->ownerId);
		return $object->getProperties()->edit($post, array('propertyValueId','value','ownerId','measureId'));
	}

	protected function ajaxEditComplectsPropertyRelation ()
	{
		$this->ajaxResponse($this->editComplectsPropertyRelation( $this->getPOST() ));
	}

	protected function editComplectsPropertyRelation ($post)
	{
		$orderGood = $this->getObject('\modules\orders\orderComplectsGoods\lib\OrderComplectsGood', $post->ownerId);
		return $orderGood->getProperties()->edit($post, array('propertyValueId','value','ownerId','measureId'));
	}

	protected function isHasParametersOrProperties($orderGood)
	{
		return ($this->getParametersByCategoryAlias($orderGood)->count() || $this->getPropertiesByCategoryAlias($orderGood)->count());
	}

	protected function editPaidPercent()
	{
		$this->ajaxResponse( $this->editPaidPercentDo() );
	}

	protected function editPaidPercentDo($orderId =null )
	{
		$this->checkUserRightAndBlock('order_edit');
		$order = $this->getObject($this->objectClass, isset($orderId) ? $orderId : $this->getGET()->orderId);
		$datenow = date('d-m-Y');
		$order->edit(array('paidPercentDate'=>$datenow));
		return $order->edit($this->getPOST());
	}

	protected function ajaxManagerConfirmOrder()
	{
		$order = $this->getObject($this->objectClass, $this->getPost()->orderId);
		$edit = $order->edit( array('partnerConfirmed'=>1) );
		if(is_int($edit))
			$this->addNotifyConfirmToHistory(new $this->objectClass($this->getPost()->orderId), 1);
		$this->ajaxResponse($edit);
	}

	protected function ajaxGetModalChangeStatusId()
	{
		echo $this->setContent('order', $this->getObject($this->objectClass, $this->getPost()['orderId']))
				->setContent('oldStatusName', $this->getPost()['oldStatusName'])
				->setContent('newStatusName', $this->getPost()['newStatusName'])
				->includeTemplate('ModalChangeStatusId');
	}

	protected function ajaxSendChangeStatusIdMessages()
	{
		$order = $this->getObject($this->objectClass, $this->getPost()['orderId']);
		$mail = new \modules\mailers\EditOrderStatusId($order);

		$res = $mail->sendMailToClient($this->getPost());
		if($res != 1)
			return $this->ajaxResponse($res);

		if($this->getPost()['toClientAndPartner'] == 'true'){
			$mail = new \modules\mailers\EditOrderStatusId($order);
			$res = $mail->sendMailToPartner($this->getPost());
		}

		$this->ajaxResponse($res);
	}

	protected function ajaxEditPaidPercentGroup()
	{
		$idsArray = explode(',', trim($this->getPost()['idsString']));
		foreach ($idsArray as $id)
			if(!is_numeric( $this->editPaidPercentDo($id) ))
				return $this->ajaxResponse('error');
		$this->ajaxResponse(1);
	}

	protected function getPartnerChangeStatusBlock($order)
	{
		$nextStatus = $this->getObject($this->objectsClass)->getStatuses()->getNextObject($order->getStatus());
		if($this->isAuthorisatedUserAnManager()  &&  in_array($nextStatus->id, $this->_config->partnersPermissibleChangeStatusesId)){
			ob_start();
			$this->setContent('nextStatus', $nextStatus)
				->includeTemplate ('partnerChangeStatusBlock');
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		return false;
	}

	protected function ajaxPartnerEditOrderStatusId()
	{
		$order = $this->getObject($this->objectClass, $this->getPost()->orderId);
		$edit = $order->edit($this->getPOST());
		if(is_numeric($edit))
			echo json_encode(array('statusBlock'=>$this->getPartnerChangeStatusBlock($order), 'status'=>$order->getStatus()->name));
	}

}
