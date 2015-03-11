<?php
namespace modules\catalog\devices\lib;
class DeviceConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\modules\catalog\CatalogValidators,
		\core\traits\validators\Sitemap,
		\core\traits\adapters\Sitemap;

	const ACTIVE_STATUS_ID  = 1;
	const BLOCKED_STATUS_ID = 2;
	const REMOVED_STATUS_ID = 5;
	const TOP_STATUS_ID     = 8;

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass  = '\modules\catalog\devices\lib\Device';
	protected $objectsClass = '\modules\catalog\devices\lib\Devices';

	public $templates         = 'modules/catalog/devices/tpl/';
	public $shopcartTemplate  = 'shopcart/standartShopcartItem';
	public $orderGoodAdminTemplate  = 'standartGoods';
	public $orderGoodClientTemplate  = 'standartGoodsForClient';
	public $imagesPath        = 'files/devices/images/';
	public $imagesUrl         = 'data/images/devices/';

	protected $table = 'catalog_devices'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'componentId',
		'description',
		'text',
		'priority',
		'date',
		'lastUpdateTime',
		'sitemapPriority',
		'changeFreq',
		'color',
		'colorTitle',
		'colorGroupId'
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => array('_adaptHtml', array('html' => true)),
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'componentId' => array(
				'validation' => array('_validInt'),
			),
			'categoryId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'colorGroupId' => array(
				'validation' => array('_validInt'),
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'lastUpdateTime' => array(
				'validation' => array('_validLastUpdateTime'),
				'adapt' => '_adaptLastUpdateTime',
			),
			'sitemapPriority' => array(
				'validation' => array('_validSitemapPriority'),
				'adapt' => '_adaptSitemapPriority',
			),
			'changeFreq' => array(
				'validation' => array('_validChangeFreq'),
				'adapt' => '_adaptChangeFreq',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
			'lastUpdateTime' => array('_outDateTime'),
		);
	}
}