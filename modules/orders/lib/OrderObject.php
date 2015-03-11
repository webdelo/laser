<?php
namespace modules\orders\lib;
class OrderObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\orders\lib\OrderConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}