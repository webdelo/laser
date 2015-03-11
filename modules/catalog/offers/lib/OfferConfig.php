<?php
namespace modules\catalog\offers\lib;
class OfferConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\core\traits\validators\Percent,
		\core\traits\validators\Price,
		\modules\catalog\CatalogValidators;

	const ACTIVE_STATUS_ID = 1;
	const REMOVED_STATUS_ID = 3;

	public $removedStatus = self::REMOVED_STATUS_ID;

	public $objectClass  = '\modules\catalog\offers\lib\Offer';
	protected $objectsClass = '\modules\catalog\offers\lib\Offers';

	public $templates  = 'modules/catalog/offers/tpl/';
	public $imagesPath = 'files/offers/images/';
	public $imagesUrl  = 'data/images/offers/';
	public $filesPath = 'files/offers/files/';
	public $filesUrl  = 'data/images/offers/';

	public $shopcartTemplate  = 'shopcart/standartShopcartItem';
	public $orderGoodAdminTemplate  = 'standartGoods';
	public $orderGoodClientTemplate  = 'standartGoodsForClient';

	protected $table = 'catalog_offers'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'goodId',
		'moduleId',
		'discount',
		'description',
		'name',
		'title',
		'domain',
		'statusId',
		'categoryId',
		'date',
		'priority',
		'type',
		'time',
		'quantity'
	);

	public function rules()
	{
		return array(
			'categoryId, statusId, moduleId', 'goodId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'priority' => array(
				'validation' => array('_validInt'),
			),
			'date, time' => array(
				'adapt' => '_adaptRegDate',
			),
			'domain, type' => array(
				'validation' => array('_validNotEmpty'),
			),
			'code, title, name' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'discount' => array(
				'validation' => array('_validPrice'),
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
			'time' => array('_outDate'),
		);
	}
}