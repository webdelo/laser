<?php
namespace modules\catalog\complects\complectGoods\lib;
class ComplectGoodConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Base,
		\core\traits\validators\Price;

	protected $objectClass  = '\modules\catalog\complects\complectGoods\lib\ComplectGood';
	protected $objectsClass = '\modules\catalog\complects\complectGoods\lib\ComplectGoods';

	public $templates  = 'modules/catalog/complects/complectGoods/tpl/';

	protected $table = 'catalog_complect_goods'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'complectId',
		'goodId',
		'quantity',
		'discount',
		'goodDescription',
		'mainGood'
	);

	public function rules()
	{
		return array(
			'complectId, goodId, quantity' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'discount' => array(
				'validation' => array('_validInt'),
			),
			'mainGood' => array(
				'adapt' => '_adaptBool',
			),
		);
	}
}