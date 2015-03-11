<?php
namespace modules\catalog\prices\lib;
class PriceConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Base;

	protected $objectClass  = '\modules\catalog\prices\lib\Price';
	protected $objectsClass = '\modules\catalog\prices\lib\Prices';

	public $templates  = 'modules/catalog/prices/tpl/';
	public $imagesPath = '';

	protected $tablePostfix = '_prices'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'objectId',
		'quantity',
		'price',
		'oldPrice',
	);

	public function rules()
	{
		return array(
			'price, oldPrice' => array(
				'adapt' => '_validPrice',
			),
			'objectId, quantity' => array(
				'validation' => array('_validInt', array('notEmpty'=>true, 'positive'=>true)),
			),
		);
	}
}