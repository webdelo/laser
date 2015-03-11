<?php
namespace modules\catalog\subGoods\lib;
class SubGood extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new SubGoodObject($objectId);
		parent::__construct($object);
	}

	public function getGood()
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->subGoodId);
	}
}