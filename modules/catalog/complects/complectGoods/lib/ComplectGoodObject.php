<?php
namespace modules\catalog\complects\complectGoods\lib;
class ComplectGoodObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\catalog\complects\complectGoods\lib\ComplectGoodConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}