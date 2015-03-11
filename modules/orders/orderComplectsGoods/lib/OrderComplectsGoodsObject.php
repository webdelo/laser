<?php
namespace modules\orders\orderComplectsGoods\lib;
class OrderComplectsGoodsObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\objects\WordsSearch;

	protected $configClass     = '\modules\orders\orderComplectsGoods\lib\OrderComplectsGoodConfig';
	protected $objectClassName = '\modules\orders\orderComplectsGoods\lib\OrderComplectsGood';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
