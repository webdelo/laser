<?php
namespace modules\managers\lib;
class ManagersObject extends \core\authorization\UserRegistrator
{
    protected $configClass = '\modules\managers\lib\ManagerConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}