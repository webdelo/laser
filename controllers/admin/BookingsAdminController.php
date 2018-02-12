<?php
namespace controllers\admin;
class BookingsAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'bookings',
		'add',
		'edit',
		'booking',
		'remove',
		'ajaxDenyMessage',
		'ajaxAcceptMessage',
		'ajaxEditStatus',
		'ajaxEditDescription',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\bookings\lib\BookingConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->bookings();
	}

	protected function bookings ()
	{
		$this->checkUserRightAndBlock('bookings');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$category = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$text = (empty($this->getGET()['text'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['text']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);
		$settled = empty($this->getGET()['settled']) ? '' : 1;


		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($category))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $category);

		if (!empty($status))
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		if (!empty($settled))
			$this->modelObject->setSubquery('AND `settled` = ?d', 1);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'bookings');
	}

	public function add()
	{
		$this->checkUserRightAndBlock('booking_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->setObject($this->_config->getObjectClass(), $objectId)
				 ->addImages();
		}
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('booking_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function booking()
	{
		$this->checkUserRightAndBlock('booking');
		$this->useRememberPastPageList();

		$booking = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$booking = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editBooking' => 'Параметры');

		$messages = $booking->getMessages();
		$messages->setOrderBy(' `date` DESC ');

		$bookings = new $this->objectsClass;
		$this->setContent('booking', $booking)
			 ->setContent('tabs', $tabs)
			 ->setContent('bookings', $bookings)
			 ->setContent('messages', $messages)
			 ->setContent('statuses', $bookings->getStatuses())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'booking');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('booking_delete');
		if (isset($this->getREQUEST()[0]))
			$bookingId = (int)$this->getREQUEST()[0];

		if (!empty($bookingId)) {
			$booking = $this->getObject($this->objectClass, $bookingId);
			$this->ajaxResponse($booking->remove());
		}
	}

	protected function ajaxAcceptMessage()
	{
		$booking = $this->getObject('\modules\bookings\lib\Booking', $this->getGET()->bookingId);
		$message = $booking->getMessages()->getObjectById($this->getREQUEST()[0]);

		$this->ajaxResponse($message->accept());
	}

	protected function ajaxDenyMessage()
	{
		$booking = $this->getObject('\modules\bookings\lib\Booking', $this->getGET()->bookingId);
		$message = $booking->getMessages()->getObjectById($this->getREQUEST()[0]);

		$this->ajaxResponse($message->deny($this->getPOST()->description));
	}

	protected function ajaxEditStatus()
	{
		$booking = $this->getObject('\modules\bookings\lib\Booking', $this->getPOST()->objectId);
		$this->ajaxResponse($booking->editField($this->getPOST()->statusId, 'statusId'));
	}

	protected function ajaxEditDescription()
	{
		$booking = $this->getObject('\modules\bookings\lib\Booking', $this->getPOST()->objectId);
		$this->ajaxResponse($booking->editField($this->getPOST()->description, 'description'));
	}
}
?>
