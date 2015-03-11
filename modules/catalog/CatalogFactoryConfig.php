<?php
namespace modules\catalog;
class CatalogFactoryConfig extends \core\modules\base\ModuleConfig
{
	use	\modules\catalog\CatalogValidators,
		\modules\catalog\CatalogAdapters,
		\core\traits\adapters\Base;

	protected  $table = 'catalog'; // set value without preffix!

	protected $objectFields = array(
		'id',
		'code',
		'name',
		'class',
	);

	public function rules()
	{
		return array(
			'code' => array(
				'validation' => array('_validUniqueCode'),
				'adapt' =>'_adaptCode',
			),
			'name' => array(
				'validation' => array('_validName'),
				'adapt' => '_adaptHtml',
			),
			'class' => array(
				'validation' => array('_validClass'),
			),
		);
	}
}