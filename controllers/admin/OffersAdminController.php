<?php
namespace controllers\admin;
class OffersAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\core\traits\controllers\Images,
		\core\traits\controllers\Files,
		\controllers\admin\traits\ListActionsAdminControllerTrait;


	protected $permissibleActions = array(
		'offers',
		'offer',
		'add',
		'edit',
		'remove',
		'getGoodTable',
		'ajaxGetGoodTable',

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
		$this->_config = new \modules\catalog\offers\lib\OfferConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();

		if($this->isAuthorisatedUserAnManager())
			$this->permissibleActions = $this->permissibleActionsForManagersUsers;
	}

	protected function defaultAction()
	{
		return $this->offers();
	}

	protected function offers()
	{
		$this->checkUserRightAndBlock('offers');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);

		$categoryId = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);
		$moduleId = (empty($this->getGET()['moduleId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['moduleId']);
		$domain = (empty($this->getGET()['domain'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['domain']);
		$type = (empty($this->getGET()['type'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['type']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$goodId = (empty($this->getGET()['goodId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['goodId']);

		if (!empty($categoryId))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $categoryId);

		unset($this->getPOST()['statusIdArray']);
		if($this->getStatusIdArray()){
			$this->getPOST()['statusIdArray'] = $this->getStatusIdArray();
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` IN ('.implode(',', $this->getPOST()['statusIdArray']).')');
		}
		else{
			$config = $this->_config;
			$this->modelObject->setSubquery('AND `statusId` != ?d', $config::REMOVED_STATUS_ID);
		}

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($moduleId)){
			$this->modelObject->setSubquery('AND `moduleId` = ?d', $moduleId);
		}

		if (!empty($domain)){
			$this->modelObject->setSubquery('AND `domain` = \'?s\'', $domain);
		}

		if (!empty($type)){
			$this->modelObject->setSubquery('AND `type` = \'?s\'', $type);
		}

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($goodId)){
			$this->modelObject->setSubquery('AND `goodId` = ?d', $goodId);
		}

		$this->modelObject->setOrderBy('`date` DESC, `id` DESC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'offers');
	}

	private function getStatusIdArray()
	{
		$statusIdArray = array();
		foreach (explode('&', $_SERVER['QUERY_STRING']) as $element){
			if( strpos($element, 'statusId') !== false )
				$statusIdArray[] = \core\utils\DataAdapt::textValid(str_replace('statusId=', '', $element));
		}
		return empty($statusIdArray[0]) ? false : $statusIdArray;
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('offer_add');
		$this->setObject($this->_config->getObjectsClass());
		$objectId = $this->modelObject->setCode($this->getPOST()['code'])
					    ->setName($this->getPOST()['name'])
					    ->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('offer_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])
			->ajax($this->modelObject->edit($this->getPOST()));
	}

	protected function offer()
	{
		$this->checkUserRightAndBlock('offer');
		$this->useRememberPastPageList();

		$offer = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$offer = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editOffer' => 'Параметры');
		$offer->id ? $tabs = array_merge($tabs, array('files' => 'Файлы')) : '';

		$offers = new $this->objectsClass;

		$this->setContent('offer', $offer)
			->setContent('tabs', $tabs)
			->setContent('offers', $offers)
			->setContent('object', $offer) // Need for files template
			 ->setContent('objects', $offers) // Need for files template
			->setContent('statuses', $offers->getStatuses())
			->setContent('mainCategories', $offers->getMainCategories(1))
			->includeTemplate($this->_config->getAdminTemplateDir().'offer');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('offer_delete');
		if (isset($this->getREQUEST()[0]))
			$offerId = (int)$this->getREQUEST()[0];
		if (!empty($offerId)) {
			$offer = $this->getObject($this->objectClass, $offerId);
			$this->ajaxResponse($offer->remove());
		}
	}

	protected function getGoodTable($goodId)
	{
		echo $this->getGoodTableContent($goodId);
	}

	private function getGoodTableContent($goodId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);
		ob_start();
			$this->setContent('good', $good)
				->includeTemplate('goodTable');
			$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	protected function ajaxGetGoodTable()
	{
		$this->ajaxResponse($this->getGoodTableContent($this->getPost()['goodId']));
	}
}