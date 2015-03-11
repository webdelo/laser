<?php
namespace controllers\admin;
class PropertiesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Categories,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'properties',
		'add',
		'edit',
		'editField',
		'property',
		'remove',
		'changePropertiesValuesPriority',
		'addPropertyValue',
		'editPropertyValue',
		'changeMeasureCategoryInValue',
		'deletePropertyValue',
		'ajaxGetPropertiesValuesBlock',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

		/* Start: Categories Trait Methods*/
		'categories',
		'categoryAdd',
		'categoryEdit',
		'category',
		'removeCategory',
		'getMainCategories',
		'changeCategoriesPriority',
		/*   End: Categories Trait Methods*/

		/* Start: Images Trait Methods*/
		'uploadImage',
		'addImagesFromEditPage',
		'removeImage',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editImage',
		'getTemplateToEditImage',
		'ajaxGetImagesBlock',
		'ajaxGetImagesListBlock',
		/*   End: Images Trait Methods*/

	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\properties\lib\PropertyConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->properties();
	}

	protected function properties ()
	{
		$this->checkUserRightAndBlock('properties');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$category = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$text = (empty($this->getGET()['text'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['text']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($category))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $category);

		if (!empty($status))
			$this->modelObject->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'properties');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('property_add');

		$this->ajax($this->addProperty($this->getPOST()), 'ajax', true);
	}

	private function addProperty($post)
	{
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($post, $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->getObject($this->objectClass, $objectId)->additionalCategories->edit($post->additionalCategories);
		}
		return $objectId;
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('property_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}
	
	protected function editField()
	{
		$this->checkUserRightAndBlock('property_edit');
		$fieldKey = array_keys($_POST)[0];
		$field    = array_shift($_POST);
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->editField($field, $fieldKey), 'ajax', true);
	}

	protected function property()
	{
		$this->checkUserRightAndBlock('property');
		$this->useRememberPastPageList();

		$property = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$property = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editProperty' => 'Параметры');

		$properties = new $this->objectsClass;
		$this->setContent('property', $property)
			 ->setContent('object', $property) // Need for images template
			 ->setContent('objects', $properties) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('properties', $properties)
			 ->setContent('statuses', $properties->getStatuses())
			 ->setContent('mainCategories', $properties->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'property');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('property_delete');
		if (isset($this->getREQUEST()[0]))
			$propertyId = (int)$this->getREQUEST()[0];

		if (!empty($propertyId)) {
			$property = $this->getObject($this->objectClass, $propertyId);
			$this->ajaxResponse($property->remove());
		}
	}

	protected function ajaxGetPropertiesValuesBlock()
	{
		echo $this->getPropertyValuesBlock($this->getREQUEST()[0]);
	}

	protected function getPropertyValuesBlock ($propertyId) {
		$property = $this->getObject($this->objectClass, (int)$propertyId);
		$measures = new \modules\measures\lib\Measures;
		$this->setContent('property', $property)
			 ->setContent('measures', $measures)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'propertiesValues');
	}

	protected function addPropertyValue()
	{
		$propertyValues = $this->getObject('\modules\properties\components\propertiesValues\lib\PropertyValues');

		$objectId =  $this->setObject($propertyValues)
						  ->modelObject
						  ->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function editPropertyValue()
	{
		$propertyValue = $this->getObject('\modules\properties\components\propertiesValues\lib\PropertyValue', $this->getPOST()['id']);
		$edit = $this->setObject($propertyValue)->modelObject->edit($this->getPOST());

		$this->ajax($edit, 'ajax', true);
	}
	
	protected function changeMeasureCategoryInValue()
	{
		$propertyValue = $this->getObject('\modules\properties\components\propertiesValues\lib\PropertyValue', $this->getPOST()['id']);
		$edit = $this->setObject($propertyValue)->modelObject->edit($this->getPOST());
		$json = array();
		
		if ($edit) {
			$measures = new \modules\measures\lib\Measures;
			$measures->setSubquery(' AND `categoryId`=?d', $this->getPOST()->measureCategoryId);
			foreach ( $measures as $measure) {
				$json[] = array(
					'value' => $measure->id,
					'name'  => $measure->name
				);
			}
			echo json_encode($json);
		}
	}

	protected function deletePropertyValue()
	{
		if (isset($this->getREQUEST()[0]))
			$propertyValueId = (int)$this->getREQUEST()[0];

		if (!empty($propertyValueId)) {
			$propertyValue = $this->getObject('\modules\properties\components\propertiesValues\lib\PropertyValue', $propertyValueId);
			$this->ajaxResponse($propertyValue->remove());
		}
	}

	protected function changePropertiesValuesPriority ()
	{
		$data = $this->getREQUEST()['data'];
		$counter = 0;
		foreach ($data as $objectId=>$priority) {
			$counter++;
			$propertyValue = new \modules\properties\components\propertiesValues\lib\PropertyValue((int)$objectId);
			$this->setObject($propertyValue)
				->modelObject->edit(array('id'=>$objectId, 'priority'=>$counter), array('id', 'priority'));
			$this->modelObject->getErrors();
		}
		echo 1;
	}
}
