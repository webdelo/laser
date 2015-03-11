<?php
namespace modules\properties\components\propertiesValues\lib;
class Relations extends \core\modules\base\ModuleDecorator
{
	private $propertiesByAliasesCache = array();
	private $propertiesByOwnerIdCache = array();
	
		
	function __construct($objectId, $configObject)
	{
		$object = new RelationsObject($objectId, $configObject);
		parent::__construct($object);
	}
}