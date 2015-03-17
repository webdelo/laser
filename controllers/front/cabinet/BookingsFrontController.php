<?php
namespace controllers\front\cabinet;
class BookingsFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\controllers\front\traits\CabinetFrontControllerTrait,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization,
		traits\profileTrait;

	protected $permissibleActions = array(
		'booking',
		'bookings',
		'archive',
		'ajaxEditDates',
		'getRightBlockByUser',
		'ajaxGetBookingList',
		'ajaxGetArchiveList',
		'ajaxGetDialogList',
		'ajaxSendMessage',
		'ajaxSetMessageAsReaded',
		'ajaxRemoveMessage',
		'ajaxConfirmRent',
		'ajaxRefuseRent',
		'ajaxBookingOptionChoice',
		'ajaxMoveToArchive',
		'ajaxMoveFromArchive',
		'ajaxMoveToFavorite',
		'ajaxMoveFromFavorite',
		'getUserProfileBlock',
		/*Start: Invoices Methods*/
		'ajaxGenerateStandartInvoice',
		'ajaxChangePrice',
		'ajaxGetInvoicesListContent',
		/*End: Invoices Methods*/
		'test',
	);

	private $detailsPage = 'booking';

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		try {
			if($this->getController('Authorization')->isGuest()) {
				return $this->getController('authorization')->authorizationPage();
			} elseif( !$this->getREQUEST()->action ) {
				return $this->defaultAction();
			} elseif((int)$name) {
				if ($this->setAction($this->detailsPage)->isPermissibleAction())
					$this->callAction($arguments);
			} elseif($this->setAction($name)->isPermissibleAction()) {
				return $this->callAction($arguments);
			} else
				return $this->redirect404();
		} catch (\exceptions\ExceptionAccess $e) {
			$this->redirect404();
		}
	}

	public function defaultAction()
	{
		$this->bookings();
	}

	protected function bookings()
	{
		return $this->setTitle('Заявки на бронирование')
					->setDescription('Заявки на бронирование')
					->setContent('subpage', 'bookings/bookings')
					->setContent('realties', $this->getRealties())
					->includeTemplate('cabinet/main');
	}

	protected function archive()
	{
		return $this->setTitle('Архив бронирований')
					->setDescription('Архив бронирований')
					->setContent('subpage', 'bookings/archive')
					->setContent('realties', $this->getRealties())
					->includeTemplate('cabinet/main');
	}

	protected function booking()
	{
		$booking = $this->getBookingList()->getObjectByCode($this->getREQUEST()->action);
		$this->checkOwnerRightsAndThrowAccessException($booking);

		$messages = $booking->getMessages();
		$messages->setOrderBy(' `date` DESC ');

		$bookingUpdateHandler = new \modules\bookings\BookingUpdateHandler($booking);
		if ($bookingUpdateHandler->isOpponentLastUpdate())
			$bookingUpdateHandler->setAsReaded();

		return $this->setTitle('Бронирование')
					->setDescription('Бронирование')
					->setContent('object', $booking)
					->setContent('updateHandler', new \modules\bookings\BookingUpdateHandler($booking))
					->setContent('messages', $messages)
					->includeTemplate('cabinet/bookings/booking/booking');
	}

	private function checkOwnerRightsAndThrowAccessException(\modules\bookings\lib\Booking $booking)
	{
		if ($this->checkOwnerRights($booking))
			return $this;
		else
			throw new \exceptions\ExceptionAccess();
	}

	private function checkPaymentAndThrowAccessException(\modules\bookings\lib\Booking $booking)
	{
		if ( $booking->isNotPaid() )
			return $this;
		else
			throw new \exceptions\ExceptionAccess();
	}

	private function checkOwnerRights(\modules\bookings\lib\Booking $booking)
	{
		return $booking->checkOwnerRights($this->getAuthorizatedUser());
	}

	protected function getRightBlockByUser($objectId)
	{
		$booking = $this->getBookingList()->getObjectById($objectId);

		return $this->setTitle('Бронирование')
					->setDescription('Бронирование')
					->setContent('object', $booking)
					->includeTemplate('cabinet/bookings/booking/rightBlock'.$this->getRightBlockTemplateNameByUser($booking));
	}

	protected function GetBookingListCount()
	{
		$objects = $this->getBookingList();
		$config  = $objects->getConfig();
		$objects->filterByRealtyCode($this->getGET()->realtyCode)
				->setOrderBy(' `lastUpdateDate` DESC, `statusId` ASC, `date` DESC ');

		if ( $this->isNoop($this->getGET()->status) ) {
			$objects->excludeOwnerArchived();
		}
		return $objects->count();
	}

	protected function ajaxGetBookingList()
	{
		$objects = $this->getBookingList();
		$config  = $objects->getConfig();
		$objects->filterByRealtyCode($this->getGET()->realtyCode)
				->sort();

		if ( $this->isNoop($this->getGET()->status) ) {
			$objects->excludeOwnerArchived();
		}

		$objects->setPager(5);

		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->includeTemplate('cabinet/bookings/bookingsList');
	}

	protected function ajaxGetArchiveList()
	{
		$objects = new \modules\bookings\lib\Bookings;
		$config  = $objects->getConfig();

		$objects->filterByOwnerId($this->getAuthorizatedUserId())
				->filterOwnerArchived()
				->filterByRealtyCode($this->getGET()->realtyCode)
				->setOrderBy(' `lastUpdateDate` DESC, `statusId` ASC, `date` DESC ');

		$objects->setPager(5);

		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->includeTemplate('cabinet/bookings/bookingsList');
	}

	protected function ajaxGetDialogList()
	{
		$this->includeDialogList($this->getREQUEST()[0]);
	}

	protected function includeDialogList($bookingId)
	{
		$booking = new \modules\bookings\lib\Booking($bookingId);
		$messages = $booking->getMessages()
							->filterModerStatus();

		$messages->setOrderBy(' `date` DESC ');

		$this->setContent('object', $booking)
			 ->setContent('messages', $messages)
			 ->includeTemplate('cabinet/bookings/booking/dialogs/dialogList');
	}

	protected function ajaxSendMessage()
	{

		$bookingActions = new \modules\bookings\BookingActions($this->getPOST()->objectId);
		$this->checkOwnerRightsAndThrowAccessException($bookingActions->getBooking());

		$this->setObject($bookingActions->getBooking())
			 ->ajax( $bookingActions->sendMessage($this->getPOST()->text), 'ajax', true);
	}

	protected function ajaxRemoveMessage()
	{
		$bookingActions = new \modules\bookings\BookingActions($this->getPOST()->bookingId);
		$this->checkOwnerRightsAndThrowAccessException($bookingActions->getBooking());

		$this->ajaxResponse($bookingActions->removeMessage($this->getPOST()->messageId));
	}

	protected function ajaxEditDates()
	{
		$booking = $this->getObject('\modules\bookings\BookingActions', $this->getPOST()->objectId);
		$this->checkOwnerRightsAndThrowAccessException($booking->getBooking());
		$this->checkPaymentAndThrowAccessException($booking->getBooking());

		$this->ajaxResponse(
			$this->needToChangeDate($booking->getBooking())
				? $booking->setPeriod($this->getPOST()->startDate, $this->getPOST()->endDate)
				: true
		);
	}

	private function needToChangeDate(\modules\bookings\lib\Booking $booking)
	{
		return $booking->startDate !== $this->getPOST()->startDate || $booking->endDate !== $this->getPOST()->endDate;
	}

	protected function ajaxChangePrice()
	{
		$mehtodName = strtolower($this->getPOST()['type']) == 'discount'
			? 'addDiscountPrice'
			: 'addSurchargePrice';
		$bookingAction = $this->getBookingActionsByPOST();
		$result = $bookingAction->$mehtodName($this->getPOST()['price']);
		$this->setObject($bookingAction)->ajax($result);
	}

	private function getBookingActionsByPOST()
	{
		return $this->getBookingActionsById($this->getPOST()['bookingId']);
	}

	private function getBookingActionsById($bookingId)
	{
		if(empty((int)$bookingId))
			throw new \Exception('Not passed BookingId in class '.get_class($this).'!');
		$booking = $this->getObject('\modules\bookings\BookingActions', $bookingId);
		$this->checkOwnerRightsAndThrowAccessException($booking->getBooking())
			 ->checkPaymentAndThrowAccessException($booking->getBooking());
		return $booking;
	}

	protected function ajaxGenerateStandartInvoice()
	{
		$bookingAction = $this->getBookingActionsByPOST();
		$bookingAction->addStandartInvoice();
		$this->ajaxResponse(true);
	}

	protected function ajaxGetInvoicesListContent()
	{
		$bookingActions = $this->getBookingActionsByPOST();
		$this->getInvoicesListContent($bookingActions->getBooking()->getInvoices());
	}

	protected function getInvoicesListContent($invoicesList)
	{
		$this->setContent('invoices', $invoicesList)
			 ->includeTemplate('cabinet/bookings/booking/invoices/invoicesContainer');
	}

	protected function getBookingBlock($object)
	{
		if ( $object->isNew() ) {
			$this->setContent('object', $object)
				 ->setContent('options', new \modules\bookings\BookingOptionActions($object->id))
				 ->includeTemplate('cabinet/bookings/booking/bookingOptions/bookingBlock');
		}
	}

	protected function getBookingDialogs($object)
	{
		$this->setContent('object', $object)
			 ->includeTemplate('cabinet/bookings/booking/dialogs/dialogsContainer');
	}

	protected function ajaxConfirmRent()
	{
		$bookingActions = $this->getObject('\modules\bookings\BookingActions', $this->getGET()->bookingId);
		$this->ajaxResponse($bookingActions->confirm());
	}

	protected function ajaxRefuseRent()
	{
		$bookingActions = $this->getObject('\modules\bookings\BookingActions', $this->getGET()->bookingId);
		$this->ajaxResponse($bookingActions->cancel($this->getGET()->reason));
	}

	protected function ajaxBookingOptionChoice()
	{
		$bookingOptions = new \modules\bookings\BookingOptionActions($this->getPOST()->bookingId);
		$option         = $this->getPOST()->option;

		$this->ajaxResponse($bookingOptions->$option($this->getPOST()));
	}

	protected function ajaxSetMessageAsReaded()
	{
		$this->ajaxResponse(
			$this->getObject('\modules\bookings\lib\Booking', $this->getGET()->objectId)
				 ->getMessages()
				 ->getObjectById($this->getGET()->messageId)
				 ->setAsReaded()
		);
	}

	protected function ajaxMoveToArchive()
	{
		$bookingActions = $this->getObject('\modules\bookings\BookingActions', (int)$this->getREQUEST()[0]);
		$this->ajaxResponse($bookingActions->moveToArchive());
	}

	protected function ajaxMoveFromArchive()
	{
		$bookingActions = $this->getObject('\modules\bookings\BookingActions', (int)$this->getREQUEST()[0]);
		$this->ajaxResponse($bookingActions->moveFromArchive());
	}

	protected function ajaxMoveToFavorite()
	{
		$bookingActions = $this->getObject('\modules\bookings\BookingActions', (int)$this->getREQUEST()[0]);
		$this->ajaxResponse($bookingActions->moveToFavorite());
	}

	protected function ajaxMoveFromFavorite()
	{
		$bookingActions = $this->getObject('\modules\bookings\BookingActions', (int)$this->getREQUEST()[0]);
		$this->ajaxResponse($bookingActions->moveFromFavorite());
	}

	protected function test()
	{
//		$time = microtime();
//		$booking = new \modules\bookings\lib\Booking(50);
//		echo 'Inst. Booking: '; echo $time-microtime(); echo "<br/>"; $time = microtime();
//		$bookingAction = new \modules\bookings\BookingActions(50);
//		echo 'Inst. BookingActions: '; echo $time-microtime(); echo "<br/>"; $time = microtime();
//		$bookingMailerHandler = new \modules\bookings\BookingMailerHandler($bookingAction);
//		echo 'Inst. BookingMailerHandler: '; echo $time-microtime(); echo "<br/>"; $time = microtime();
//		$invoice = new \modules\bookings\payments\bookingInvoices\lib\BookingInvoice(106);
//		echo 'Inst. BookingInvoice: '; echo $time-microtime(); echo "<br/>"; $time = microtime();
//		$message = new \modules\messages\lib\Message(604, $booking);
//		echo 'Inst. Message: '; echo $time-microtime(); echo "<br/>"; $time = microtime();
//		$bookingSMSHandler->activate();
//		$bookingSMSHandler->cancel('I do not want hire my apartaments for crazy russians! Go out from Ukraine, fufaechniki!');
//		$bookingMailerHandler->invoicePaid($invoice);
//		echo 'Inst. sending InvoicePaid(): '; echo $time-microtime(); echo "<br/>"; $time = microtime();
//		$bookingSMSHandler->sendMessage($message);
//		$bookingSMSHandler->removeMessage($message);
//		$bookingSMSHandler->addSurchargePrice(20);
//		$bookingSMSHandler->addDiscountPrice(25);
//		$bookingSMSHandler->addStandartInvoice($invoice);
//		echo 'Sended!';
	}
}
