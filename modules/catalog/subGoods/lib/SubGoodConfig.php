<?php
namespace modules\catalog\subGoods\lib;
class SubGoodConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass  = '\modules\catalog\subGoods\lib\SubGood';
	protected $objectsClass = '\modules\catalog\subGoods\lib\SubGoods';

	public $templates  = 'modules/catalog/subGoods/tpl/';

	protected $table = 'catalog_subgoods'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'goodId',
		'subGoodId',
		'subGoodQuantity',
	);

	public function rules()
	{
		return array(
			'goodId, subGoodId, subGoodQuantity' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			)
		);
	}
}
