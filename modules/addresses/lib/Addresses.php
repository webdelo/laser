<?php
namespace modules\addresses\lib;
class Addresses extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\addresses\lib\AddressConfig';

	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}

}