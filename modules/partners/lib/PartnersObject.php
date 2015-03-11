<?php
namespace modules\partners\lib;
class PartnersObject extends \core\modules\base\ModuleObjects
{
    	protected $configClass     = '\modules\partners\lib\PartnerConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}