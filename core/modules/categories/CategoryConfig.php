<?php
namespace core\modules\categories;
class CategoryConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Alias;

	protected $objectClass = '\core\modules\categories\Category';
	protected $objectsClass = '\core\modules\categories\Categories';

	protected $tablePostfix = '_categories'; // set value without preffix!\
	protected $idField = 'id';
	protected $removedStatus = 3;
	protected $objectFields = array(
		'id',
		'statusId',
		'parentId',
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
		'image',
		'bigImage',
		'domainAlias',
		'headerText'
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
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'parent_category' => array(
				'validation' => array('_validInt'),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'metaTitle, metaKeywords, metaDescription, headerText, image, bigImage' => array(
				'adapt' => '_adaptHtml',
			),
		);
	}

	public function _validNotEmpty($data)
	{
		return !empty($data);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

	public function _outDate($data)
	{
		return \core\utils\Dates::convertDate($data, 'simple');
	}

	public function _adaptRegDate($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : time() ;
	}

}