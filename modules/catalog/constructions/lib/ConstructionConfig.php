<?php
namespace modules\catalog\constructions\lib;
class ConstructionConfig extends \core\modules\base\ModuleConfig
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

	const VELOPARKOVKI_CATEGORY_ALIAS = 'veloparkovki';
	const SKAMEIKI_CATEGORY_ALIAS     = 'skameyki_i_lavochki';
	const VOLYERY     = 'volyery';
	const SVARNYE_BESEDKI_CATEGORY_ALIAS = 'svarnye_besedki';
	const KOVANYE_BESEDKI_CATEGORY_ALIAS = 'kovanye_besedki';
	const BESEDKI_CATEGORY_ALIAS = 'besedki';

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass  = '\modules\catalog\constructions\lib\Construction';
	protected $objectsClass = '\modules\catalog\constructions\lib\Constructions';

	public $templates  = 'modules/catalog/constructions/tpl/';
	public $shopcartTemplate  = 'shopcart/standartShopcartItem';
	public $orderGoodAdminTemplate  = 'standartGoods';
	public $orderGoodClientTemplate  = 'standartGoodsForClient';
	public $imagesPath = 'files/constructions/images/';
	public $imagesUrl  = 'data/images/constructions/';

	protected $table = 'catalog_constructions'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'componentId',
		'description',
		'text',
		'delivery',
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
			'componentId' => array(
				'validation' => array('_validInt'),
			),
			'categoryId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
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