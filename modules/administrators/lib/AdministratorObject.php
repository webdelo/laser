<?php
namespace modules\administrators\lib;
class AdministratorObject extends \core\authorization\AuthorizatedUserObject
{
	protected $configClass = '\modules\administrators\lib\AdministratorConfig';
	protected $rightsKey = 'rights';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

}