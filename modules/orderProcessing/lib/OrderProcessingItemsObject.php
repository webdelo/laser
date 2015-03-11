<?php
namespace modules\orderProcessing\lib;
class OrderProcessingItemsObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\objects\WordsSearch;
        
	protected $configClass     = '\modules\orderProcessing\lib\OrderProcessingItemConfig';
	protected $objectClassName = '\modules\orderProcessing\lib\OrderProcessingItem';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
