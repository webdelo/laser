<?php
namespace modules\fabricators\lib;
class FabricatorConfig extends \core\modules\base\ModuleConfig
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
	const REMOVED_STATUS_ID = 3;

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass  = '\modules\fabricators\lib\Fabricator';
	protected $objectsClass = '\modules\fabricators\lib\Fabricators';

	public $templates  = 'modules/fabricators/tpl/';
	public $imagesPath = 'files/fabricators/images/';
	public $imagesUrl  = 'data/images/fabricators/';
	public $filesPath = 'files/fabricators/files/';
	public $filesUrl  = 'data/files/fabricators/';

	protected $table = 'fabricators'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'categoryId',
		'statusId',
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
		'foundDate'
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
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
			)
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
			//'lastUpdateTime' => array('_outDateTime'),
		);
	}

}
