<?php
namespace core\modules\images;
class ImageConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Alias,
		\core\traits\adapters\Date;

	const SECONDARY_CATEGORY_ID = 1;
	const PRIMARY_CATEGORY_ID = 2;
	const STATUS_ACTIVE = 1;
	const STATUS_BLOCKED = 1;

	protected $objectClass = '\core\modules\images\Image';
	protected $objectsClass = '\core\modules\images\Images';

	protected $tablePostfix = '_images'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'alias',
		'name',
		'title',
		'description',
		'date',
		'priority',
		'objectId',
		'statusId',
		'categoryId',
		'focus',
		'sharpen',
		'rgbBgColor',
		'extension',
	);

	public function rules()
	{
		return array(
			'title, name' => array(
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'tmpName' => array(
				'validation' => array('_validFileExists'),
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'priority' => array(
				'validation' => array('_validInt'),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'extension' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

	public function _validFileExists($key)
	{
		if (!file_exists(DIR.$this->data[$key])) {
			$this->addError($key, 'File does not exists');
			return false;
		}
		return true;
	}
}