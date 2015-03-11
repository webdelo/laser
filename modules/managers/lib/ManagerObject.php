<?php
namespace modules\managers\lib;
class ManagerObject extends \core\authorization\AuthorizatedUserObject
{
	protected $configClass = '\modules\managers\lib\ManagerConfig';
	protected $rightsKey = 'rights';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

}