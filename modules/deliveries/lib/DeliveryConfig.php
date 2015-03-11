<?php
namespace modules\deliveries\lib;
class DeliveryConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;
	const STATUS_DELETED = 3;

	const STANDART_PRICE_DELIVERY_ID = 234;
	
	const POST_CATEGORY_ID = 10;
	const TO_CLIENT_CATEGORY_ID = 8;

	const FLOAT_PRICE_DELIVERY_ID = 223;

	public $constructionsId = 9;
	public $devicesId = 21;

	protected $objectClass  = '\modules\deliveries\lib\Delivery';
	protected $objectsClass = '\modules\deliveries\lib\Deliveries';

	public $templates  = 'modules/deliveries/tpl/';

	protected $table = 'deliveries'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'priority',
		'name',
		'alias',
		'price',
		'basePrice',
		'flexibleAddress',
		'description',
		'date',
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'flexibleAddress' => array(
				'adapt' => '_adaptBool',
			),
			'price, basePrice' => array(
				'validation' => array('_validInt'),
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'categoryId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

	public function getFloatPriceDeliveryId()
	{
		return self::FLOAT_PRICE_DELIVERY_ID;
	}
	
	public function getStandartPriceDeliveryId()
	{
		return self::STANDART_PRICE_DELIVERY_ID;
	}

}
