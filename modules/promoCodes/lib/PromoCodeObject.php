<?php
namespace modules\promoCodes\lib;
class PromoCodeObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\promoCodes\lib\PromoCodeConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

}