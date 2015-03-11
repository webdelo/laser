<?php
namespace modules\catalog\complects\lib;
class ComplectConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\modules\catalog\CatalogValidators,
		\core\traits\validators\Price,
		\core\traits\adapters\User;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;
	const REMOVED_STATUS_ID = 3;

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass  = '\modules\catalog\complects\lib\Complect';
	protected $objectsClass = '\modules\catalog\complects\lib\Complects';

	public $templates  = 'modules/catalog/complects/tpl/';
	public $shopcartTemplate  = 'shopcart/complectShopcartItem';
	public $orderGoodAdminTemplate  = 'complectGoods';
	public $orderGoodClientTemplate  = 'complectGoodsForClient';

	protected $table = 'catalog_complects'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'moduleId',
		'domain',
		'statusId',
		'description',
		'priority',
		'date',
		'managerId'

	);

	public function rules()
	{
		return array(
			'statusId, moduleId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'managerId' => array(
				'validation' => array('_validInt'),
				'adapt' => '_adaptUser',
			),
			'priority' => array(
				'validation' => array('_validInt'),
			),
			'domain' => array(
				'validation' => array('_validNotEmpty'),
			),
			'code, name' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => 'adaptHtml',
			),
			'description' => array(
				'adapt' => 'adaptHtml',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}
}