<?php
namespace modules\orders\lib;
class OrderConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\core\traits\validators\Percent,
		\core\traits\validators\Price;


	const PAYMENT_INVOICE_STATUS_ID = 2;
	const START_PAYMENT_STATUS_ID = 1;
	const PAID_ORDER_STATUS_ID = 3;
	const PAID_BY_FACT_ORDER_STATUS_ID = 4;


	const NEW_ORDER_STATUS_ID = 1;
	const AGREED_STATUS_ID = 2;
	const ON_PRODUCTION_ORDER_STATUS_ID = 4;
	const ON_DELIVERY_ORDER_STATUS_ID = 5;
	const COMPLETED_ORDER_STATUS_ID = 6;
	const CANCELED_STATUS_ID = 8;
	const REMOVED_STATUS_ID = 9;

	const FROM_SITE_CATEGORY_ID = 2;

	public $removedStatus = self::REMOVED_STATUS_ID;
	public $canceledStatus = self::CANCELED_STATUS_ID;
	public $completedStatus = self::COMPLETED_ORDER_STATUS_ID;
	public $paidStatus = 3;

	public $currentOrdersId = array(
		self::NEW_ORDER_STATUS_ID,
		self::PAYMENT_INVOICE_STATUS_ID,
		self::PAID_ORDER_STATUS_ID,
		self::ON_PRODUCTION_ORDER_STATUS_ID
	);
	public $archiveOrdersId = array(
		self::COMPLETED_ORDER_STATUS_ID
	);

	public $partnersPermissibleChangeStatusesId = array(
		self::ON_DELIVERY_ORDER_STATUS_ID,
		self::COMPLETED_ORDER_STATUS_ID
	);

	protected $objectClass  = '\modules\orders\lib\Order';
	protected $objectsClass = '\modules\orders\lib\Orders';

	public $templates  = 'modules/orders/tpl/';
	public $filesPath = 'files/orders/files/';
	public $filesUrl  = 'data/images/orders/';

	protected $table = 'orders'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'moduleId',
		'domain',
		'categoryId',
		'statusId',
		'paymentStatusId',
		'cashPayment',
		'description',
		'priority',
		'date',
		'deliveryId',
		'deliveryPrice',
		'deliveryBasePrice',
		'deliveryDate',
		'deliveryTime',

		'invoiceNrDate',
		'paymentOrderNrDate',

		'paidPercent',
		'paidPercentDate',
		'information',
		'deadline',
		'nr',
		'invoiceNr',
		'paymentOrderNr',
		'rate',
		'partnerId',
		'partnerNotified',
		'partnerConfirmed',
		'managerId',
		'clientId',
		'country',
		'city',
		'street',
		'home',
		'block',
		'flat',
		'person',
		'phone',
		'cashRate',
		'partnerNotifyHistory',
		'lastNotify',
		'lastConfirmedNotify',
		'index',
		'region',

		'promoCodeId',
		'promoCodeDiscount',

		'type',
		'tracking',
		'addedBy'
	);

	private $promoCodes;

	public function rules()
	{
		return array(
			'categoryId, statusId, paymentStatusId, moduleId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'nr' => array(
				'adapt' => '_adaptNr',
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'deliveryDate' => array(
				'adapt' => '_adaptDate',
			),
			'paymentOrderNrDate' => array(
				'adapt' => '_adaptDate',
			),
			'invoiceNrDate' => array(
				'adapt' => '_adaptDate',
			),
			'deadline' => array(
				'adapt' => '_adaptDate',
			),
			'paidPercentDate' => array(
				'adapt' => '_adaptDate',
			),
			'partnerId, managerId' => array(
				'validation' => array('_validInt'),
			),
			'partnerConfirmed, partnerNotified, paidPercent' => array(
				'adapt' => '_adaptBool',
			),
			'clientId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'region' => array(
				'adapt' => '_adaptHtml',
			),
			'city, street' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'country, home, phone, domain' => array(
				'validation' => array('_validNotEmpty'),
			),
			'cashRate' => array(
				'validation' => array('_validPercent'),
			),
			'index' => array(
				'validation' => array('_validNumber'),
			),
			'promoCodeId' => array(
				'validation' => array('_validPromoCodeId'),
			),
			'cashPayment' => array(
				'validation' => array('_validInt'),
			)
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
			'deliveryDate' => array('_outDate'),
			'deadline' => array('_outDate'),
			'invoiceNrDate' => array('_outDate'),
			'paidPercentDate' => array('_outDate'),
			'paymentOrderNrDate' => array('_outDate')
		);
	}

	public function _adaptNr($key)
	{
		$orders = new \modules\orders\lib\Orders();
//		$this->data[$key] = empty($this->data['id']) ? $orders->getNextId() + 1000 : $this->data['id'];
		$this->data[$key] = empty($this->data[$key]) ? $orders->getNextId() + 1000 : $this->data[$key];
	}

	public function _validPromoCodeId($data)
	{
		if (empty($data))
			return true;
		return $this->getPromoCodes()->isExists($data);
	}

	private function getPromoCodes()
	{
		if (empty($this->promoCodes))
			$this->promoCodes = new \modules\promoCodes\lib\PromoCodes();
		return $this->promoCodes;
	}
}