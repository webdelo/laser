<?php
namespace modules\catalog\subGoods\lib;
class SubGoodObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\catalog\subGoods\lib\SubGoodConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}