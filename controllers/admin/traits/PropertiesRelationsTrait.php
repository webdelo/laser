<?php
namespace controllers\admin\traits;
trait PropertiesRelationsTrait
{	
	protected function ajaxGetPropertiesBlocks()
	{
		echo $this->getPropertiesBlocks($this->getGET()->objectId);
	}

	protected function getPropertiesBlocks($objectId)
	{
		$realty = $this->getObject($this->objectClass, $objectId);
		$measures = new \modules\measures\lib\Measures;		
		$properties = new \modules\properties\lib\Properties;
		$this->setContent('object', $realty)
			 ->setContent('measures', $measures)
			 ->setContent('properties', $properties)
			 ->includeTemplate('propertiesBlocks');
	}
	
	protected function ajaxEditPropertyRelation () {
		$this->ajaxResponse($this->editPropertyRelation( $this->getPOST() ));
	}
	
	protected function editPropertyRelation ($post) {
		$objects = new $this->objectsClass();
		$object = $objects->getObjectById($post->ownerId);
		
		return $object->getProperties()->edit($post, array('propertyValueId','value','ownerId','measureId'));
	}
}
