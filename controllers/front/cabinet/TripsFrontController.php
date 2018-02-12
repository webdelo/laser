<?php
namespace controllers\front\cabinet;
class TripsFrontController extends \controllers\base\Controller
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
		'trip',
		'trips',
		'archive',
		'getRightBlockByUser',
		'ajaxGetTripsList',
		'ajaxGetArchiveList',
		'ajaxGetDialogList',
		'ajaxSendMessage',
		'ajaxSetMessageAsReaded',
		'ajaxRemoveMessage',
		'ajaxGetInvoicesListContent',
		'ajaxEditDates',
		'ajaxCancelRent',
		'ajaxMoveToArchive',
		'ajaxMoveFromArchive',
		'getUserProfileBlock',
	);

	private $detailsPage = 'trip';

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		try {
			if($this->getController('Authorization')->isGuest())
				return $this->getController('authorization')->authorizationPage();
			elseif( !$this->getREQUEST()->action )
				return $this->defaultAction();
			elseif((int)$name) {
				if ($this->setAction($this->detailsPage)->isPermissibleAction())
					$this->callAction($arguments);
			} elseif($this->setAction($name)->isPermissibleAction()) {
				return $this->callAction($arguments);
			}else {
				return $this->redirect404();
			}
		} catch (\exceptions\ExceptionAccess $e) {
			$this->redirect404();
		}
	}

	public function defaultAction()
	{
		$this->trips();
	}

	protected function trips()
	{
		$statuses = $this->getTripsList()->getStatuses();
		$statuses->setOrderBy(' `priority` ASC ');

		return $this->setTitle('Заявки на бронирование')
					->setDescription('Заявки на бронирование')
					->setContent('subpage', 'trips/trips')
					->setContent('statuses', $statuses)
					->includeTemplate('cabinet/main');
	}

	protected function archive()
	{
		return $this->setTitle('Архив поездок')
					->setDescription('Архив поездок')
					->setContent('subpage', 'trips/archive')
					->includeTemplate('cabinet/main');
	}

	protected function trip()
	{
		$trip = $this->getTripsList()->getObjectByCode($this->getREQUEST()->action);
		$this->checkClientRightsAndThrowAccessException($trip);

		$messages = $trip->getMessages();
		$messages->setOrderBy(' `date` DESC ');

		$bookingUpdateHandler = new \modules\bookings\BookingUpdateHandler($trip);
		if ($bookingUpdateHandler->isOpponentLastUpdate())
			$bookingUpdateHandler->setAsReaded();

		return $this->setTitle('Бронирование')
					->setDescription('Просмотр бронирования')
					->setContent('object', $trip)
					->setContent('messages', $messages)
					->includeTemplate('cabinet/trips/trip/trip');
	}

	protected function ajaxGetInvoicesListContent()
	{
		$tripActions = $this->getTripActions($this->getPOST()->bookingId);
		$this->getInvoicesListContent($tripActions->getTrip()->getInvoices());
	}

	private function getTripActions($tripId)
	{
		if(empty((int)$tripId))
			throw new \Exception('Not passed TripId in class '.get_class($this).'!');

		$trip = $this->getObject('\modules\trips\TripActions', $tripId);
		$this->checkClientRightsAndThrowAccessException($trip->getTrip());

		return $trip;
	}

	protected function getInvoicesListContent($invoicesList)
	{
		$this->setContent('invoices', $invoicesList)
			 ->includeTemplate('cabinet/trips/trip/invoices/invoicesContainer');
	}

	protected function getTripsList()
	{
		return $this->getAuthorizatedUser()->getTrips();
	}

	protected function GetTripsListCount()
	{
		$objects = $this->getTripsList();
		$config  = $objects->getConfig();
		$objects->filterByRealtyCode($this->getGET()->realtyCode)
				->setOrderBy(' `lastUpdateDate` DESC, `statusId` ASC, `date` DESC ');

		if ( $this->isNoop($this->getGET()->status) ) {
			$objects->excludeClientArchived();
		}
		return $objects->count();
	}

	protected function ajaxGetTripsList()
	{
		$objects = $this->getTripsList();
		$config  = $objects->getConfig();
		$objects->filterByRealtyCode($this->getGET()->realtyCode)
				->sort();

		if ( $this->isNoop($this->getGET()->status) ) {
			$objects->excludeClientArchived();
		}

		$objects->setPager(5);

		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->includeTemplate('cabinet/trips/tripsList');
	}

	protected function ajaxGetArchiveList()
	{
		$objects = new \modules\bookings\lib\Bookings;
		$config  = $objects->getConfig();

		$objects->filterByClientId($this->getAuthorizatedUserId())
				->filterClientArchived()
				->filterByRealtyCode($this->getGET()->realtyCode)
				->setOrderBy(' `lastUpdateDate` DESC, `statusId` ASC, `date` DESC ');

		$objects->setPager(5);

		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->includeTemplate('cabinet/trips/tripsList');
	}

	protected function ajaxGetDialogList()
	{
		$this->includeDialogList($this->getREQUEST()[0]);
	}

	protected function includeDialogList($tripId)
	{
		$tripActions = $this->getTripActions($tripId);
		$messages = $tripActions->getTrip()
								->getMessages()
								->filterModerStatus();

		$messages->setOrderBy(' `date` DESC ');

		$this->setContent('object', $tripActions->getTrip())
			 ->setContent('messages', $messages)
			 ->includeTemplate('cabinet/trips/trip/dialogs/dialogList');
	}

	protected function ajaxSendMessage()
	{
		$tripActions = $this->getTripActions($this->getPOST()->objectId);
		if ( !$tripActions->getTrip()->clientVisit() ) {
			$this->ajaxResponse(false);
			return;
		}

		$this->setObject($tripActions->getTrip())
			 ->ajax( $tripActions->sendMessage($this->getPOST()->text) , 'ajax', true);
	}

	protected function ajaxRemoveMessage()
	{
		$tripActions = $this->getTripActions($this->getPOST()->tripId);
		if ( !$tripActions->getTrip()->clientVisit() ) {
			$this->ajaxResponse(false);
		}
		$this->ajaxResponse($tripActions->removeMessage($this->getPOST()->messageId));
	}

	private function checkClientRightsAndThrowAccessException(\modules\bookings\lib\Booking $booking)
	{
		if ( $booking->clientVisit() )
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

	protected function ajaxEditDates()
	{
		$trip = $this->getTripActions($this->getPOST()->objectId);
		$this->checkClientRightsAndThrowAccessException($trip->getTrip());
		$this->checkPaymentAndThrowAccessException($trip->getTrip());

		$this->ajaxResponse(
			$this->needToChangeDate($trip->getTrip())
				? $trip->setPeriod($this->getPOST()->startDate, $this->getPOST()->endDate)
				: true
		);
	}

	private function needToChangeDate(\modules\bookings\lib\Booking $trip)
	{
		return $trip->startDate !== $this->getPOST()->startDate || $trip->endDate !== $this->getPOST()->endDate;
	}

	protected function ajaxCancelRent()
	{
		$tripActions = $this->getTripActions($this->getGET()->tripId);
		$this->ajaxResponse($tripActions->cancel($this->getGET()->reason));
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
		$tripActions = $this->getTripActions((int)$this->getREQUEST()[0]);
		$this->ajaxResponse($tripActions->moveToArchive());
	}

	protected function ajaxMoveFromArchive()
	{
		$tripActions = $this->getTripActions((int)$this->getREQUEST()[0]);
		$this->ajaxResponse($tripActions->moveFromArchive());
	}
}
