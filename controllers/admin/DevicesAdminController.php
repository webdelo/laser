<?php
namespace controllers\admin;
class DevicesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\RequestHandler,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\core\traits\controllers\Images,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'devices',
		'device',
		'add',
		'edit',
		'remove',
		'ajaxGetMainContentBlock',
		'changePriority',
		'groupActions',
		'categoriesGroupActions',
		'groupRemove',
		'getLastDevices',
		'setParametersRelationToCategory',
		'ajaxGetOtherColor',
		'ajaxAssociateDevicesByColor',
		'ajaxDeassociateDevice',
		'ajaxGetAssociatedDevicesTemplate',
		'getAssociatedDevicesTemplate',
		'getDevicesObject',

		// Start: Categories Trait Methods
		'categories',
		'categoryAdd',
		'categoryEdit',
		'category',
		'removeCategory',
		'getMainCategories',
		'changeCategoriesPriority',
		//   End: Categories Trait Methods

		// Start: Images Trait Methods
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
		// End: Images Trait Methods
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\catalog\devices\lib\DeviceConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->devices();
	}

	protected function devices()
	{
		$this->checkUserRightAndBlock('devices');
		$this->rememberPastPageList($_REQUEST['controller']);
		$this->setObject($this->objectsClass);

		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$category = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$code = (empty($this->getGET()['code'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['code']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$text = (empty($this->getGET()['text'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['text']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($category)){
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $category);
			$categoryObject = new \core\modules\categories\Category($category, $this->_config);
			if($categoryObject->getChildrenIdString())
				$this->modelObject->setSubquery('OR `categoryId` IN (?s)', $categoryObject->getChildrenIdString());
		}

		if (!empty($status))
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($name))
			$this->modelObject->setSubquery('AND `id` IN (SELECT `id`  FROM `'.\modules\catalog\CatalogFactory::getInstance()->mainTable().'` WHERE LOWER(`name`) LIKE \'%?s%\')', strtolower($name));

		if (!empty($code))
			$this->modelObject->setSubquery('AND
					`id` IN (SELECT `id`  FROM `'.\modules\catalog\CatalogFactory::getInstance()->mainTable().'` WHERE LOWER(`code`) LIKE \'%?s%\')
					OR
					`id` IN (SELECT `objectId`  FROM `tbl_catalog_domainsinfo` WHERE LOWER(`code`) LIKE \'%?s%\')'
				, strtolower($code), strtolower($code));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		if ( $this->getGET()->categoryId ) {
			$category = $this->getObject('\modules\catalog\categories\CatalogCategory', (int)$this->getGET()->categoryId, $this->_config);
			$parameters = $this->getObject('\modules\parameters\lib\Parameters')
							   ->getParametersByCategoryId((int)$category->getParametersCategory()->id);
			$this->setContent('parameters', $parameters);
		}

		$this->setContent('objects', $this->modelObject)
			 ->setContent('components', new \modules\components\lib\Components)
			 ->setContent('pager', $this->modelObject->getPager())
			 ->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'devices');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('device_add');
		$this->setObject($this->_config->getObjectsClass());
		$objectId = $this->modelObject->setCode($this->getPOST()['code'])
					    ->setName($this->getPOST()['name'])
					    ->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('device_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])
		     ->ajax($this->modelObject->edit($this->getPOST()));
	}

	protected function device()
	{
		$this->checkUserRightAndBlock('device');
		$this->useRememberPastPageList();

		$object = isset($this->getREQUEST()[0])
			? $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0])
			: new \core\Noop();
		$objects = new $this->objectsClass;

		$this->setContent('object', $object)
			 ->setContent('objects', $objects)
			 ->setContent('components', new \modules\components\lib\Components())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'device');
	}

	protected function ajaxGetMainContentBlock()
	{
		return $this->getMainContentBlock($this->getPOST()['objectId']);
	}

	protected function getMainContentBlock($catalogId)
	{
		$object = isset($catalogId)
			? \modules\catalog\CatalogFactory::getInstance()->getGoodById($catalogId)
			: new \core\Noop();

		$objects = new $this->objectsClass;

		$tabs = array('editDevice' => 'Параметры');
		if ($object->id) {
			$parameters = $this->getObject('\modules\parameters\lib\Parameters')->getParametersByCategoryId((int)$object->getCategory()->getParametersCategory()->id);
			$this->setContent('parameters', $parameters);
			$tabs = array_merge($tabs, array(
				'prices' => 'Ценообразование',
				'parameters' => 'Характеристики',
				'colors' => 'Указать цвет'
			));
		}

		$this->setContent('object', $object)
			 ->setContent('objects', $objects)
			 ->setContent('components', new \modules\components\lib\Components())
			 ->setContent('tabs', $tabs)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'main');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('device_delete');
		if (isset($this->getREQUEST()[0]))
			$objectId = (int)$this->getREQUEST()[0];

		if (!empty($objectId)) {
			$object = $this->getObject($this->_config->getObjectClass(), $objectId);
			$this->ajaxResponse($object->remove());
		}
	}

	protected function categoriesGroupActions ()
	{
		if (empty($this->getPOST()['parametersCategoryId']) && empty($this->getPOST()['statusId']) && empty($this->getPOST()['parentId'])) {
			echo $this->ajaxResponse(array('parametersCategoryId'=>'Выберите изменяемое свойство!'));
		} else
			if (empty($this->getPOST()['group']))
				echo $this->ajaxResponse(array('code'=>'2', 'message'=>'Выберите объекты для изменения'));
			else {
				foreach ($this->getPOST()['group'] as $key=>$objectId) {
					$data = $this->getPOST();
					unset($data['group']);
					if (empty($data['categoryId']))
						unset($data['categoryId']);
					if (empty($data['statusId']))
						unset($data['statusId']);

					$object = $this->getObject('\modules\catalog\categories\CatalogCategory', $objectId, $this->_config);
					$data['name'] = $object->getName();
					$data['parametersCategoryId'] = $object->parametersCategoryId;
					if ($this->getPOST()->categoryGroupAction !== 'parametersCategoryId') {
						$data['parameters'] = $object->getParametersRelationArray();
					}
					$this->setObject($object)->ajax($this->modelObject->edit($data), 'ajax', true);
				}
			}
	}
	protected function groupActions ()
	{
		if (empty($_POST['categoryId']) && empty($_POST['statusId']) && empty($_POST['parametersCategoryId'])) {
			echo $this->ajaxResponse(array('categoryId'=>'Выберите изменяемое свойство!'));
		} else
			if (empty($_POST['group']))
				echo $this->ajaxResponse(array('code'=>'2', 'message'=>'Выберите объекты для изменения'));
			else {
				foreach ($_POST['group'] as $key=>$objectId) {
					$data = array();
					$data = array_merge($_POST, $data);
					unset($data['group']);
					if (empty($data['categoryId']))
						unset($data['categoryId']);
					if (empty($data['statusId']))
						unset($data['statusId']);


					$object = $this->getObject($this->objectClass, $objectId);
					$data['name'] = $object->getName();
					$data['code'] = $object->getCode();
					$data['additionalCategories'] = $object->additionalCategoriesArray;

					if ($this->getPOST()->getAction !== 'parametersId') {
						$data['parametersValues'] = $object->getParametersArray();
					}
					$data = new \core\ArrayWrapper($data);
					$this->setObject($object)->ajax($this->modelObject->edit($data), 'ajax', true);
				}
			}
	}

	protected function getLastDevices($quantity)
	{
		$this->setObject( '\modules\catalog\devices\lib\Devices' );
		$objects = $this->modelObject->setOrderBy('`date` DESC, `id` DESC')->setLimit($quantity);
		return $this->checkUserRight('devices') ? $objects : false;
	}

	protected function categories ()
	{
		$this->rememberPastPageList($_REQUEST['controller']);

		$objects = new $this->objectsClass;
		$objectsCategories = $objects->getCategories();

		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$parentCategory = (empty($this->getGET()['parentCategoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['parentCategoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$objectsCategories->setSubquery('AND `id` = ?d', $this->getGET()['id']);
		if (!empty($parentCategory))
			$objectsCategories->setSubquery('AND `parentId` = ?d', $parentCategory=='baseCategories' ? 0 : $parentCategory);
		if (!empty($status))
			$objectsCategories->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);
		if (!empty($this->getGET()['id']))
			$objectsCategories->setSubquery('AND `id` = ?d', $this->getGET()['id']);
		if (!empty($alias))
			$objectsCategories->setSubquery('AND `alias` LIKE \'%?s%\'', $alias);
		if (!empty($name))
			$objectsCategories->setSubquery('AND `name` LIKE \'%?s%\'', $name);

		$objectsCategories->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('categories', $objectsCategories)
			->setContent('pager', $objectsCategories->getPager())
			->setContent('pagesList', $objectsCategories->getQuantityItemsOnSubpageListArray())
			->setContent('statuses', $objectsCategories->getStatuses())
			->setContent('objects', $objects)
			->setContent('parametersCategories', $this->getObject('\modules\parameters\lib\Parameters')->getCategories())
			->includeTemplate(lcfirst($this->objectsClassName).'Categories');
	}

	protected function category ()
	{
		$this->useRememberPastPageList();

		$objectsObject = new $this->objectsClass;
		$category = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$category = new \modules\catalog\categories\CatalogCategory((int)$this->getREQUEST()[0], $this->_config);

		$tabs = array('editCategory' => 'Основная информация');
		if ( !$this->isNoop($category) ) {
			$tabs = array_merge($tabs, array('categoriesParameters'=>'Характеристики'));
			$parameters = $this->getObject('\modules\parameters\lib\Parameters')->getParametersByCategoryId((int)$category->getParametersCategory()->id);
			$this->setContent('parameters', $parameters)
				 ->setContent('parametersCategories', $parameters->getCategories());
		}

		$this->setContent('mainCategories', $objectsObject->getMainCategories())
			 ->setContent('statuses', $objectsObject->getMainCategories()->getStatuses())
			 ->setContent('tabs', $tabs)
			 ->setContent('object', $category)
			 ->includeTemplate(lcfirst($this->objectsClassName).'Category');
	}

	protected function categoryEdit()
	{
		$post = $this->getPOST();
		$category = $this->getObject('\modules\catalog\categories\CatalogCategory', $post->id, $this->_config);
		$result = $category->edit($post);
		$this->setObject($category)->ajax($result, 'ajax');
	}

	public function ajaxGetParametersSelect()
	{
		$category = null;
		$post = $this->getPOST();
		$get = $this->getGET();
		$parameters = $this->getObject('\modules\parameters\lib\Parameters')->getParametersByCategoryId((int)$post->parametersCategoryId);
		if($get->objectId)
			$category = $this->getObject('\modules\catalog\categories\CatalogCategory', $get->objectId, $this->_config);
		echo $this->includeParametersSelect($parameters, $category);
	}

	private function includeParametersSelect($parameters, $object=NULL)
	{
		if (!$object)
			$object = $this->getNoop();
		$this->setContent('parameters', $parameters)
			 ->setContent('object', $object)
			 ->includeTemplate('parameters/parametersOptions');
	}

	public function getTemplateParametersForCategory($categoryId)
	{
		$category = $this->getObject('\modules\catalog\categories\CatalogCategory', $categoryId, $this->_config);
		$this->setContent('category', $category)
			 ->setContent('category', $category)
			 ->includeTemplate('categoriesParameters');
	}

	public function getFormToNewParameterAdding($object)
	{
		$this->setContent('object', $object)
			 ->includeTemplate('parameters/newParameter');
	}

	protected function ajaxGetOtherColor()
	{
		$objects = \modules\catalog\CatalogFactory::getInstance()->getGoodsByNameOrCode($this->getGET()->q);

		foreach ($objects as $object) {
			try {
				$json = array();
				$json['value'] = $object->id;
				$json['name'] = $object->getName();
				$json['code'] = $object->getCode();
				$json['price'] = is_object($object->getPriceByMinQuantity()) ? 'no price' : $object->getPriceByMinQuantity();
				$json['basePrice'] = is_object($object->getBasePriceByMinQuantity()) ? 'no basePrice' : $object->getBasePriceByMinQuantity();
				if($json['basePrice'] == null)
					$json['basePrice'] = '0';
			} catch (\exceptions\ExceptionPrice $exc) {
				$json['price'] =  'no price';
				$json['basePrice'] =  'no basePrice';
			}
			$data[] = $json;
		}

		if (!isset($data)) {
			$data[0]['value'] = 'no value';
			$data[0]['name'] = 'Результатов не найдено';
			$data[0]['code'] = 'no code';
			$data[0]['price'] = 'no price';
			$data[0]['basePrice'] = 'no basePrice';
		}

		echo json_encode($data);
	}

	protected function ajaxAssociateDevicesByColor()
	{
		$this->ajaxResponse($this->associateDevicesByColor($this->getPOST()));
	}

	protected function associateDevicesByColor($data)
	{
		$device = $this->getObject('\modules\catalog\devices\lib\Device', $data->goodId);
		$fields = array('colorGroupId');

		if ( $device->colorGroupId ) {
			if ( $data->otherColorId ) {
				$sameDevice = $this->getObject('\modules\catalog\devices\lib\Device', $data->otherColorId);
				if (empty($sameDevice->colorGroupId)) {
					$colorData = array( 'colorGroupId' => $device->colorGroupId );
					$fields = array('colorGroupId');
					return $sameDevice->getParentObject()->edit($colorData, $fields);
				} else {
					return array('otherColor'=>'Выбраное вами устройство уже принадлежит другой связке');
				}
			}
			return array('otherColor' => 'Выберите пожалуйста модель для связки с текущим телефоном.');
		} else {
			if ( $data->otherColorId ) {
				$sameDevice = $this->getObject('\modules\catalog\devices\lib\Device', $data->otherColorId);
				if ($sameDevice->colorGroupId) {
					$device->getParentObject()->edit(array( 'colorGroupId' => $sameDevice->colorGroupId ), $fields);
				} else {
					$groupId = $sameDevice->getGroups()->addGroup($data);
					if ( !is_int($groupId) )
						throw new \Exception ('System has a problem with color group adding!');
					$colorData = array( 'colorGroupId' => $groupId );

					$sameDevice->getParentObject()->edit($colorData, $fields);
					$device->getParentObject()->edit($colorData, $fields);
					return $groupId;
				}
			}
		}
		return array('otherColor' => 'Выберите пожалуйста модель для связки с текущим телефоном.');
	}

	protected function ajaxDeassociateDevice()
	{
		$this->ajaxResponse($this->deassociateDevice($this->getGET()->id));
	}

	protected function deassociateDevice($id)
	{
		$device = $this->getObject('\modules\catalog\devices\lib\Device', $id);
		$devices = $this->getObject('\modules\catalog\devices\lib\Devices')->setSubquery(' AND `colorGroupId`=?d ', $device->colorGroupId);
		if ( $devices->count() == 1 ){
			$device->getGroup()->remove();
		}
		return $device->getParentObject()->edit(array( 'colorGroupId' => 0 ), array('colorGroupId'));
	}

	protected function ajaxGetAssociatedDevicesTemplate()
	{
		echo $this->getAssociatedDevicesTemplate($this->getObject($this->objectClass, $this->getGET()->objectId));
	}

	protected function getAssociatedDevicesTemplate($object)
	{
		 $this->setContent('object', $object)
			  ->includeTemplate('associatedDevices');
	}

	protected function getDevicesObject()
	{
		if (empty($this->devices))
			$this->devices = new \modules\catalog\devices\lib\Devices();
		return $this->devices;
	}
}