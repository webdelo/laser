<?php
//	Для вывода своего темплейта нужно переопределить два метода
//	1. getTemplateWithClient
//	2. getTemplateToSearchClient
//	В них нужно указать путь к другим файлам шаблонов
namespace controllers\admin\traits;
trait PropertiesRelationsTrait
{	
	protected function ajaxGetPropertiesBlocks()
	{
		echo $this->getPropertiesBlocks($this->getGET()->objectId);
	}

	protected function getPropertiesBlocks($objectId)
	{
		$object = $this->getObject($this->objectClass, $objectId);
		$measures = new \modules\measures\lib\Measures;		
		$properties = new \modules\properties\lib\Properties;
		$this->setContent('object', $object)
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
		$properties = $object->getProperties();
		
		return $properties->edit($post, array('propertyValueId','value','ownerId','measureId'));
	}
}
