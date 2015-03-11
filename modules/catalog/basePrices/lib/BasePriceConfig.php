<?php
namespace modules\catalog\basePrices\lib;
class BasePriceConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Base;

	protected $objectClass  = '\modules\catalog\basePrices\lib\BasePrice';
	protected $objectsClass = '\modules\catalog\basePrices\lib\BasePrices';

	public $templates  = 'modules/catalog/basePrices/tpl/';
	public $imagesPath = '';

	protected $tablePostfix = '_baseprices'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'objectId',
		'partnerId',
		'price',
		'history'
	);

	public function rules()
	{
		return array(
			'price' => array(
				'validation' => array('_validInt', array('notEmpty'=>false)),
				'adapt' => '_adaptInt',
			),
			'objectId, partnerId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
		);
	}
}