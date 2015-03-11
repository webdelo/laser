<?php
namespace controllers\admin;
class FabricatorsAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Categories,
		\core\traits\controllers\Images,
		\core\traits\controllers\Files,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ParametersRelationsTrait,
		\controllers\admin\traits\PropertiesRelationsTrait,
		\controllers\admin\traits\AddedPropertiesRelationsTrait,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const STATUS_DELETED = 3;

	protected $permissibleActions = array(
		'fabricators',
		'add',
		'edit',
		'fabricator',
		'remove',

		/* Start: Properties Trait Methods*/
		'getPropertiesBlocks',
		'ajaxGetPropertiesBlocks',
		'ajaxEditPropertyRelation',
		/* End: Properties Trait Methods*/

		/* Start: AddedProperties Trait Methods*/
		'getSizesandweightBlocks',
		'ajaxGetSizesandweightBlocks',
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
		'download'
		/*   End: Files Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\fabricators\lib\FabricatorConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->fabricators();
	}

	protected function fabricators ()
	{
		//$this->checkUserRightAndBlock('fabricators');
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
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);

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
			->includeTemplate($this->_config->getAdminTemplateDir().'fabricators');
	}

	protected function add()
	{
		//$this->checkUserRightAndBlock('fabricator_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->setObject($this->_config->getObjectClass(), $objectId)
				 ->addImages();
		}
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		//$this->checkUserRightAndBlock('fabricator_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])
			 ->ajax($this->modelObject->edit($this->getPOST()));
	}

	protected function fabricator()
	{
		//$this->checkUserRightAndBlock('fabricator');
		$this->useRememberPastPageList();

		$fabricator = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$fabricator = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editFabricator' => 'Параметры и фото');
		if ($fabricator->id)
			$tabs = array_merge($tabs, array(
					'files' => 'Файлы',
					'services'     => 'Услуги',
					'parameters' => 'Параметры'
				));

		$fabricators = new $this->objectsClass;
		$this->setContent('fabricator', $fabricator)
			 ->setContent('object', $fabricator) // Need for images template
			 ->setContent('objects', $fabricators) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('fabricators', $fabricators)
			 ->setContent('statuses', $fabricators->getStatuses())
			 ->setContent('mainCategories', $fabricators->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'fabricator');
	}

	protected function remove()
	{
		//$this->checkUserRightAndBlock('fabricator_delete');
		if (isset($this->getREQUEST()[0]))
			$fabricatorId = (int)$this->getREQUEST()[0];

		if (!empty($fabricatorId)) {
			$fabricator = $this->getObject($this->objectClass, $fabricatorId);
			$this->ajaxResponse($fabricator->remove());
		}
	}
}
