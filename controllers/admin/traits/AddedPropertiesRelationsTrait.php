<?php
namespace controllers\admin\traits;
trait AddedPropertiesRelationsTrait
{
	protected function ajaxGetSizeBlock()
	{
		echo $this->getSizeBlock($this->getGET()->objectId);
	}

	protected function getSizeBlock($objectId)
	{
		$object = $this->getObject($this->objectClass, $objectId);
		$measures = new \modules\measures\lib\Measures;
		$properties = new \modules\properties\lib\Properties;
		$propertiesConfig = $properties->getConfig();
		$properties->getPropertiesById(array($propertiesConfig::PROPERTY_SPECS_ID));

		$this->setContent('object', $object)
			 ->setContent('measures', $measures)
			 ->setContent('properties', $properties)
			 ->includeTemplate('sizeBlock');
	}

	/*protected function ajaxGetServicesBlocks()
	{
		echo $this->getServicesBlocks($this->getGET()->objectId);
	}

	protected function getServicesBlocks($objectId)
	{
		$object = $this->getObject($this->objectClass, $objectId);
		$measures = new \modules\measures\lib\Measures;
		$properties = new \modules\properties\lib\Properties;
		$propertiesConfig = $properties->getConfig();
		$properties->getPropertiesById(array($propertiesConfig::PROPERTY_DELIVERY_ID, $propertiesConfig::PROPERTY_LIFTING_ID, $propertiesConfig::PROPERTY_FITTING_ID));

		$this->setContent('object', $object)
			 ->setContent('measures', $measures)
			 ->setContent('properties', $properties)
			 ->includeTemplate('servicesBlocks');
	}
	 */
}
