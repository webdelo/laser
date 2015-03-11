<?php
namespace modules\orders\orderGoods\lib;
class OrderGoodConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Base,
		\core\traits\validators\Price;

	protected $objectClass  = '\modules\orders\orderGoods\lib\OrderGood';
	protected $objectsClass = '\modules\orders\orderGoods\lib\OrderGoods';

	public $templates  = 'modules/orders/orderGoods/tpl/';

	protected $table = 'order_goods'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'orderId',
		'goodId',
		'quantity',
		'price',
		'basePrice',
		'goodDescription'
	);

	public function rules()
	{
		return array(
			'orderId, goodId, quantity' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'price, basePrice' => array(
				'validation' => array('_validCost'),
			),
		);
	}
}