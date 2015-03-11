<?php
namespace modules\catalog\items\lib;
class CatalogItemConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\modules\catalog\CatalogValidators,
		\core\traits\validators\Sitemap,
		\core\traits\adapters\Sitemap;

	const ACTIVE_STATUS_ID  = 1;
	const BLOCKED_STATUS_ID = 9;
	const REMOVED_STATUS_ID = 10;

	protected $objectClass  = '\modules\catalog\items\lib\CatalogItem';
	protected $objectsClass = '\modules\catalog\items\lib\Catalog';

	public $templates  = 'modules/catalog/items/tpl/';
	public $shopcartTemplate  = 'shopcart/standartShopcartItem';
	public $orderGoodAdminTemplate  = 'standartGoods';
	public $imagesPath = 'files/catalog/images/';
	public $imagesUrl  = 'data/images/catalog/';
	public $filesPath = 'files/catalog/files/';
	public $filesUrl  = 'data/files/catalog/';

	protected $table = 'catalog_items'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'fabricatorId',
		'model',
		'defects',
		'deliveryPrice',
		'condition',
		'description',
		'text',
		'priority',
		'date',
		'lastUpdateTime',
		'sitemapPriority',
		'changeFreq',
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'categoryId, statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			/*'fabricatorId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),*/
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