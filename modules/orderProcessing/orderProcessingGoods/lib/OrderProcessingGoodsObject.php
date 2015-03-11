<?php
namespace modules\orderProcessing\orderProcessingGoods\lib;
class OrderProcessingGoodsObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\objects\WordsSearch;
        
	protected $configClass     = '\modules\orderProcessing\orderProcessingGoods\lib\OrderProcessingGoodConfig';
	protected $objectClassName = '\modules\orderProcessing\orderProcessingGoods\lib\OrderProcessingGood';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
