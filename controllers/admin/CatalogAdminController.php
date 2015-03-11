<?php
namespace controllers\admin;
class CatalogAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\RequestHandler,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\core\traits\controllers\Images,
		\core\traits\controllers\Files,
		\controllers\admin\traits\ParametersRelationsTrait,
		\controllers\admin\traits\PropertiesRelationsTrait,
		\controllers\admin\traits\AddedPropertiesRelationsTrait,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'catalog',
		'catalogItem',
		'add',
		'edit',
		'remove',
		'ajaxGetMainContentBlock',
		'getCatalogObject',
		'getLastItems',

		/* Start: Properties Trait Methods*/
		'getPropertiesBlocks',
		'ajaxGetPropertiesBlocks',
		'ajaxEditPropertyRelation',
		/* End: Properties Trait Methods*/

		/* Start: AddedProperties Trait Methods*/
		'getSizeBlock',
		'ajaxGetSizeBlock',
		'getServicesBlocks',
		'ajaxGetServicesBlocks',
		/* End: AddedProperties Trait Methods*/

		/* Start: Parameters Trait Methods*/
		'getParameterBlocks',
		'ajaxGetParameterBlocks',
		/* End: Parameters Trait Methods*/

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

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
		'createTable',
		// End: Images Trait Methods

		/* Start: Files Trait Methods*/
		'uploadFile',
		'addFilesFromEditPage',
		'removeFile',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editFile',
		'getTemplateToEditFile',
		'ajaxGetFilesBlock',
		'ajaxGetFilesListBlock',
		'getFileIcon',
		'download',
		/*   End: Files Trait Methods*/

		'updateDescriptions',

		'getSeriaIdSelect',
		'ajaxGetSerialIdSelect'
	);

	protected $permissibleActionsForManagersUsers = array(
		'catalog',
		'catalogItem',
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\catalog\items\lib\CatalogItemConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();

		if($this->isAuthorisatedUserAnManager())
			$this->permissibleActions = $this->permissibleActionsForManagersUsers;
	}

	protected function defaultAction()
	{
		return $this->catalog();
	}

	protected function catalog()
	{
		$this->checkUserRightAndBlock('constructions');
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
			$this->modelObject->setSubquery('AND `id` IN (SELECT `id`  FROM `'.\modules\catalog\CatalogFactory::getInstance()->mainTable().'` WHERE LOWER(`code`) LIKE \'%?s%\')', strtolower($code));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			 ->setContent('pager', $this->modelObject->getPager())
			 ->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'catalog');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('construction_add');
		$this->setObject($this->_config->getObjectsClass());

		$post = $this->getPOST();
		$config = $this->_config;
		$post['statusId'] = $config::ACTIVE_STATUS_ID;

		$objectId = $this->modelObject->setCode($post['code'])
									  ->setName($post['name'])
									  ->add($post, $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('construction_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])
			 ->ajax($this->modelObject->edit($this->getPOST()));
	}

	protected function catalogItem()
	{
		$this->checkUserRightAndBlock('construction');
		$this->useRememberPastPageList();

		$object = isset($this->getREQUEST()[0])
			? $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0])
			: new \core\Noop();

		$this->setContent('object', $object)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'catalogItem');
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

		$tabs = array('editItem' => 'Общая информация');
		if ($object->id) {
			$tabs = array_merge($tabs, array(
//				'files' => 'Файлы',
				'parameters' => 'Параметры',
				'size' => 'Свойства',
				'subGoods'   => 'Комплектация',
				'prices'     => 'Цены',
				//'services'     => 'Услуги'
			));
		}

		$this->setContent('object', $object)
			 ->setContent('objects', $objects)
			 ->setContent('tabs', $tabs)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'main');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('construction_delete');
		if (isset($this->getREQUEST()[0]))
			$objectId = (int)$this->getREQUEST()[0];

		if (!empty($objectId)) {
			$object = $this->getObject($this->_config->getObjectClass(), $objectId);
			$this->ajaxResponse($object->remove());
		}
	}

	protected function category ()
	{
		$this->useRememberPastPageList();

		$objectsObject = new $this->objectsClass;
		$category = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$category = new \modules\catalog\categories\CatalogCategory((int)$this->getREQUEST()[0], $this->_config);

		$tabs = array('editCategory' => 'Основная информация');

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


	protected function getCatalogObject()
	{
		if (empty($this->catalog))
			$this->catalog = new \modules\catalog\items\lib\Catalog();
		return $this->catalog;
	}

	protected function getLastItems($quantity)
	{
		$this->setObject( '\modules\catalog\items\lib\Catalog' );
		$objects = $this->modelObject->setOrderBy('`date` DESC, `id` DESC')->setLimit($quantity);
		return $this->checkUserRight('constructions') ? $objects : false;
	}
	
	protected function getSeriaIdSelect($fabricatorId = false)
	{
		if($fabricatorId){
			$fabricator = $this->getObject('\modules\fabricators\lib\Fabricator', $fabricatorId);
			$this->setContent('serias', $fabricator->getParameters()->count() ? $fabricator->getParameters() : false);
		}
		$this->includeTemplate($this->_config->getAdminTemplateDir().'seriaIdSelect');
	}

	protected function ajaxGetSerialIdSelect()
	{
		if(isset( $this->getPost()['objectId'] ))
			$this->setContent('object', $this->getObject($this->objectClass, (int)$this->getPost()['objectId']));
		echo ( $this->getSeriaIdSelect((int)$this->getPost()['fabricatorId']) );
	}
}
