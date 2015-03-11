<?php
namespace modules\components\lib;
class ComponentConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	const ACTIVE_STATUS_ID = 1;
	const BLOCKED_STATUS_ID = 2;

	const TOP_MENU_CATEGORY_ID = 57;
	const NEWS_CATEGORY_ID = 82;
	CONST NEWS_DEVICES_CATEGORY_ID = 92;
	const INFORMATION_CATEGORY_ID = 59;
	const RECOMMENDATIONS_CATEGORY_ID = 90;
	const REVIEWS_CATEGORY_ID = 87;

	protected $objectClass  = '\modules\components\lib\Component';
	protected $objectsClass = '\modules\components\lib\Components';

	public $templates  = 'modules/components/tpl/';
	public $imagesPath = 'files/components/images/';
	public $imagesUrl  = 'data/images/components/';

	protected $table = 'components'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'statusId',
		'name',
		'title',
		'alias',
		'description',
		'priority',
		'date'
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'title' => array(
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'description' => array(
				'adapt' => '_adaptHtml',
			)
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
		);
	}

}
