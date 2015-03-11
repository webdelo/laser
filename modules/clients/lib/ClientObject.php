<?php
namespace modules\clients\lib;
class ClientObject extends \core\authorization\AuthorizatedUserObject
{
	protected $configClass = '\modules\clients\lib\ClientConfig';
	protected $rightsKey = 'rights';

	function __construct($objectId, $configClass = null)
	{
		$configClass = isset($configClass) ? $configClass : $this->configClass;
		parent::__construct($objectId, new $configClass);
	}
}