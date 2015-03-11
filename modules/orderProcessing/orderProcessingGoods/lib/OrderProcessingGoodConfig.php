<?php
namespace modules\orderProcessing\orderProcessingGoods\lib;
class OrderProcessingGoodConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\core\traits\validators\Sitemap,
		\core\traits\adapters\Sitemap;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;

	protected $objectClass  = '\modules\orderProcessing\orderProcessingGoods\lib\OrderProcessingGood';
	protected $objectsClass = '\modules\orderProcessing\orderProcessingGoods\lib\OrderProcessingGoods';

	public $templates  = 'modules/orderProcessing/tpl/';

	protected $table = 'order_processing_goods'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'orderId',
		'goodId',
		'quantity',
		'price',
		'basePrice',
		'class',
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
