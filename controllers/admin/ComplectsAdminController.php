<?php
namespace controllers\admin;
class ComplectsAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\core\traits\controllers\Files,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const MANAGER_USER_CLASS  = '\modules\managers\lib\Manager';

	protected $permissibleActions = array(
		'complects',
		'complect',
		'add',
		'edit',
		'remove',
		'getActiveManagers',
		'getActivePartners',
		'getComplectClientById',
		'getComplectGoodById',
		'getComplectsQuantityByStatus',
		'getComplectStatusIdByStatus',

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
		$this->_config = new \modules\catalog\complects\lib\ComplectConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->complects();
	}

	protected function complects()
	{
		$this->checkUserRightAndBlock('complects');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);

		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);
		$manager = (empty($this->getGET()['managerId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['managerId']);
		$goodId = (empty($this->getGET()['goodId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['goodId']);
		$moduleId = (empty($this->getGET()['moduleId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['moduleId']);
		$domain = (empty($this->getGET()['domain'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['domain']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		unset($this->getPOST()['statusIdArray']);
		if($this->getStatusIdArray()){
			$this->getPOST()['statusIdArray'] = $this->getStatusIdArray();
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` IN ('.implode(',', $this->getPOST()['statusIdArray']).')');
		}

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($manager))
			$this->modelObject->setSubquery('AND `managerId` = ?d', $manager);

		if (!empty($name))
			$this->modelObject->setSubquery('AND `id` IN (SELECT `id`  FROM `'.$this->getObject('\modules\catalog\CatalogFactoryConfig')->mainTable().'` WHERE `name` LIKE \'%?s%\')', $name);

		if (!empty($goodId)){
			$this->modelObject->setSubquery('AND `id` IN (SELECT `complectId`  FROM `'.$this->getObject('\modules\catalog\complects\complectGoods\lib\ComplectGoods')->mainTable().'` WHERE `goodId` = ?d)', $goodId);
		}

		if (!empty($moduleId)){
			$this->modelObject->setSubquery('AND `moduleId` = ?d', $moduleId);
		}

		if (!empty($domain)){
			$this->modelObject->setSubquery('AND `domain` = \'?s\'', $domain);
		}

		$this->modelObject->setOrderBy('`date` DESC, `id` DESC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'complects');
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
		$this->checkUserRightAndBlock('complect_add');
		$this->setObject($this->_config->getObjectsClass());
		$objectId = $this->modelObject->setCode($this->getPOST()['code'])
					    ->setName($this->getPOST()['name'])
					    ->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId);
	}

	protected function edit()
	{
		$filteredPost = $this->isAuthorisatedUserAnManager() ? $this->filterPostFields() : $this->getPOST();
		$this->checkUserRightAndBlock('complect_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])
		     ->ajax($this->modelObject->edit($this->getPOST()));
	}

	protected function complect()
	{
		$this->checkUserRightAndBlock('complect');
		$this->useRememberPastPageList();

		$complect = new \core\Noop();
		if (isset($this->getREQUEST()[0])){
			$complect = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
			if($this->isAuthorisatedUserAnManager())
				$this->checkPartnerRightForComplect($complect->partnerId);
		}


		$tabs = array(
					'editComplect' => 'Параметры',
					'goodsComplect'=>'Товары',
					);

		$complects = new $this->objectsClass;
		$settings = new \core\Settings();
		$settings = $settings->getSettings('*', array('where' => array('query' => 'type="'.TYPE.'" OR type="all"')));

		$this->setContent('complect', $complect)
			->setContent('tabs', $tabs)
			->setContent('complects', $complects)
			->setContent('object', $complect) // Need for files template
			 ->setContent('objects', $complects) // Need for files template
			->setContent('statuses', $complects->getStatuses())
			->setContent('mainCategories', $complects->getMainCategories(1))
			->setContent('activePartners', $this->getActivePartners())
			->setContent('activeManagers', $this->getActiveManagers())
			->setContent('client', $this->getComplectClientById($complect->clientId))
			->setContent('settingsRate', $settings['rate'])
			->includeTemplate($this->_config->getAdminTemplateDir().'complect');
	}

	private function checkPartnerRightForComplect($partnerId)
	{
		$authorizatedManager = $this->getAuthorizatedManager();
		if( $authorizatedManager->isManagerBelongsToPartner($partnerId) )
			return $this;
		else{
			echo 'Access denied';
			throw new \exceptions\ExceptionAccess();
		}
	}

	private function getAuthorizatedManager()
	{
		return $this->getObject(self::MANAGER_USER_CLASS, $this->getAuthorizatedUser()->id);
	}

	protected function getActiveManagers()
	{
		$administrators = $this->getObject('\modules\administrators\lib\Administrators');
		return $administrators->getActiveManagers();
	}

	protected function getActivePartners()
	{
		$partners = $this->getObject('\modules\partners\lib\Partners');
		return $partners->getActivePartners();
	}

	protected function getComplectClientById($clientId)
	{
		return $clientId ? $this->getObject('\modules\clients\lib\Client', $clientId) : $this->getNoop();
	}

	protected function getComplectGoodById($goodId)
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('complect_delete');
		if (isset($this->getREQUEST()[0]))
			$complectId = (int)$this->getREQUEST()[0];
		if (!empty($complectId)) {
			$complect = $this->getObject($this->objectClass, $complectId);
			$this->ajaxResponse($complect->remove());
		}
	}

	protected function getComplectsQuantityByStatus($status)
	{
		if( ! $this->checkUserRight('complects'))
			return false;

		$complects = new $this->objectsClass;

		$where['query'] = 'mt.statusId=?d';
		$where['data'] = array($this->getComplectStatusIdByStatus($status));

		if($this->isAuthorisatedUserAnManager()){
			$partner = $this->getAuthorizatedManagerPartner();
			$where['query'] .= ' AND mt.partnerId=?d';

			$where['data'][] = $partner->id;
		}

		$filter = array('where' => $where);
		return $complects->countAll($filter);
	}

	protected function getComplectStatusIdByStatus($status)
	{
		return $this->_config->$status;
	}

	private function getAuthorizatedManagerPartner()
	{
		$authorizatedManager = $this->getAuthorizatedManager();
		return $this->getObject('\modules\partners\lib\Partner', $authorizatedManager->partnerId);
	}
}