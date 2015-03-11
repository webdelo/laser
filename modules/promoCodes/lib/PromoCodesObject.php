<?php
namespace modules\promoCodes\lib;
class PromoCodesObject extends \core\modules\base\ModuleObjects
{
    protected $configClass = '\modules\promoCodes\lib\PromoCodeConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}