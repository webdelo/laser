<?php
namespace modules\orders\orderComplectsGoods\lib;
class OrderComplectsGoodConfig extends \core\modules\base\ModuleConfig
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

	protected $objectClass  = '\modules\orders\orderComplectsGoods\lib\OrderComplectsGood';
	protected $objectsClass = '\modules\orders\orderComplectsGoods\lib\OrderComplectsGoods';

	public $templates  = 'modules/orders/tpl/';

	protected $table = 'order_complects_goods'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'orderId',
		'goodId',
		'parentId',
		'class',
		'goodDescription'
	);

	public function rules()
	{
		return array(
			'orderId, goodId, quantity, parent' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'price, basePrice' => array(
				'validation' => array('_validCost'),
			),
		);
	}

}
