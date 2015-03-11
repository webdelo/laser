<?php
namespace modules\orderProcessing\orderProcessingComplectsGoods\lib;
class OrderProcessingComplectsGoodsObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\objects\WordsSearch;

	protected $configClass     = '\modules\orderProcessing\orderProcessingComplectsGoods\lib\OrderProcessingComplectsGoodConfig';
	protected $objectClassName = '\modules\orderProcessing\orderProcessingComplectsGoods\lib\OrderProcessingComplectsGood';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
