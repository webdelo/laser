<?php
namespace core\modules\relations;
class Relation extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new RelationObject($objectId, $configObject);	
		parent::__construct($this->decorate($object, $configObject));
	}
	
	private function decorate($object, $config){
		if ($config->objectDecorators) {
			foreach($config->objectDecorators as $decorator) {
				$object = new $decorator($object);
			}
		}
		return $object;
	}
	
}