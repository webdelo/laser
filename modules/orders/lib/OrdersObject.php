<?php
namespace modules\orders\lib;
class OrdersObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\orders\lib\OrderConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
