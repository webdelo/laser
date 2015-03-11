<?php
namespace modules\partners\lib;
class PartnerObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\partners\lib\PartnerConfig';
	protected $rightsKey = 'rights';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

}