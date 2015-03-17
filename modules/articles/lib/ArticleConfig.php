<?php
namespace modules\articles\lib;
class ArticleConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate,
		\core\traits\outAdapters\OutBase,
		\core\traits\validators\Sitemap,
		\core\traits\adapters\Sitemap;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;

	const TOP_MENU_CATEGORY_ID = 57;
	const NEWS_CATEGORY_ID = 82;
	CONST NEWS_DEVICES_CATEGORY_ID = 92;
	const INFORMATION_CATEGORY_ID = 59;
	const RECOMMENDATIONS_CATEGORY_ID = 90;
	const REVIEWS_CATEGORY_ID = 87;
	const INFORMATOR_ARTICLES_CATEGORY_ID = 95;

	protected $objectClass  = '\modules\articles\lib\Article';
	protected $objectsClass = '\modules\articles\lib\Articles';

	public $templates  = 'modules/articles/tpl/';
	public $imagesPath = 'files/articles/images/';
	public $imagesUrl  = 'data/images/articles/';
	public $filesPath = 'files/articles/files/';
	public $filesUrl  = 'data/files/articles/';

	public $blockedStatus = self::BLOCKED_STATUS_ID;

	public $notShowTitleArticlesId = array(413, 416, 415, 530, 414);

	protected $table = 'articles'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
		'blank',
		'redirect',
		'priority',
		'name',
		'h1',
		'alias',
		'description',
		'text',
		'date',
		'metaTitle',
		'metaKeywords',
		'metaDescription',
		'headerText',
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
			'h1' => array(
				'adapt' => '_adaptHtml',
			),
			'description' => array(
				'adapt' => '_adaptHtml',
			),
			'text' => array(
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'categoryId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'metaTitle, metaKeywords, metaDescription, headerText' => array(
				'adapt' => '_adaptHtml',
			),
			'blank' => array(
				'adapt' => '_adaptBool',
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
			'h1' => array('_outHtml'),
			'metaTitle' => array('_outHtml'),
			'metaKeywords' => array('_outHtml'),
			'metaDescription' => array('_outHtml'),
			'headerText' => array('_outHtml'),
			'description' => array('_outHtml'),
			'text' => array('_outHtml'),
			//'lastUpdateTime' => array('_outDateTime'),
		);
	}

}
