<?php
namespace controllers\front\cabinet;
class InvoicesFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\controllers\front\traits\CabinetFrontControllerTrait,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization,
		\controllers\front\cabinet\traits\invoiceDetailsTrait;

	protected $permissibleActions = array(
		'invoice',
		'invoices',
		'forPrint',
	);

	protected $detailsPage = 'invoice';

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
			}else
				return $this->redirect404();
		} catch (\exceptions\ExceptionAccess $e) {
			$this->redirect404();
		}
	}

	public function defaultAction()
	{
		$this->invoices();
	}

	protected function invoices()
	{
		return $this->setTitle('Счета на оплату')
					->setDescription('Счета на оплату')
					->setContent('subpage', 'invoices')
					->includeTemplate('cabinet/main');
	}

	protected function invoice()
	{
		$invoice = $this->getObject('\modules\bookings\payments\bookingInvoices\lib\BookingInvoice', $this->getREQUEST()->action);
		$this->checkUsersRightsAndThrowAccessException($invoice->getBooking());

		return $this->setTitle('Счет на оплату')
					->setDescription('Счет на оплату')
					->setContent('object', $invoice)
					->setContent('bodyType', 'article')
					->includeTemplate('cabinet/invoices/invoice/invoice');
	}

	protected function forPrint()
	{
		$invoice = $this->getObject('\modules\bookings\payments\bookingInvoices\lib\BookingInvoice', $this->getREQUEST()[0]);
		$this->checkUsersRightsAndThrowAccessException($invoice->getBooking());

		return $this->setTitle('Счет на оплату')
					->setDescription('Счет на оплату')
					->setContent('object', $invoice)
					->includeTemplate('cabinet/invoices/invoice/forPrint');
	}

	private function checkUsersRightsAndThrowAccessException(\modules\bookings\lib\Booking $booking)
	{
		if ($this->checkUsersRights($booking))
			return $this;
		else
			throw new \exceptions\ExceptionAccess();
	}

	private function checkUsersRights(\modules\bookings\lib\Booking $booking)
	{
		return $booking->ownerVisit() || $booking->clientVisit();
	}
}
