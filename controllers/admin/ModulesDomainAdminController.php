<?php
namespace controllers\admin;
class ModulesDomainAdminController extends \controllers\base\ModulesDomainBaseController
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const domainsTable = 'tbl_modules_domains';

	protected $permissibleActions = array(
		'modulesDomain',
		'add',
		'edit',
		'moduleDomain',
		'getDomainsByModuleAlias',
		'remove',
		'getModules',
		'getAllDomains',

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
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\modulesDomain\lib\ModuleDomainConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->modulesDomain();
	}

	protected function modulesDomain ()
	{
		$this->checkUserRightAndBlock('modulesDomain');
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
			$this->modelObject->setSubquery('AND `alias` LIKE \'%?s%\'', $alias);

		if (!empty($name))
			$this->modelObject->setSubquery('AND `name` LIKE \'%?s%\'', $name);

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('modulesDomain', $this->modelObject)
			->setContent('statuses', $this->modelObject->getStatuses())
			->setContent('mainCategories', $this->modelObject->getMainCategories())
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'modulesDomain');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('moduleDomain_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if($objectId)
			$this->addDomains($objectId);
		$this->ajax($objectId);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('moduleDomain_edit');
		$object = $this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id']);
		$edit = $this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if($edit)
			$this->addDomains();
		$object->ajax($edit);
	}

	private function addDomains($moduleId=null)
	{
		\core\db\Db::getMysql()->query('DELETE FROM `'.self::domainsTable.'` WHERE `moduleId` = ?d', array($this->getPOST()['id']));
		$moduleId = $moduleId ? $moduleId : $this->getPOST()['id'];
		if($this->getPOST()['domains'])
			foreach ($this->getPOST()['domains'] as $domain=>$value)
				\core\db\Db::getMysql()->query('INSERT INTO `'.self::domainsTable.'` (moduleId, domainName) VALUES (?d, "?s")', array($moduleId, $domain));
	}

	protected function moduleDomain()
	{
		$this->checkUserRightAndBlock('moduleDomain');
		$this->useRememberPastPageList();

		$moduleDomain = new \core\Noop();

		if (isset($this->getREQUEST()[0])) {
			$moduleDomain = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
		}
		$tabs = array('editModuleDomain' => 'Параметры');

		$modulesDomain = new $this->objectsClass;
		$this->setContent('moduleDomain', $moduleDomain)
			->setContent('tabs', $tabs)
			->setContent('modulesDomain', $modulesDomain)
			->setContent('statuses', $modulesDomain->getStatuses())
			->setContent('mainCategories', $modulesDomain->getMainCategories(1))
			->setContent('domains', $this->getCheckedDomains($moduleDomain->id))
			->includeTemplate($this->_config->getAdminTemplateDir().'moduleDomain');
	}

	private function getCheckedDomains($domainId)
	{
		$allDomains = $this->getAllDomains();
		$moduleDomains = $this->getDomainsByDomainId($domainId);
		$domains = array();
		$i = 0;
		foreach ($allDomains as $key=>$value){
			$domains[$i]['name'] = $key;
			$domains[$i]['selected'] = in_array($key, $moduleDomains) ? 'on' : 'off';
			$i++;
		}
		return $domains;
	}

	public function getDomainsByDomainId($domainId)
	{
		$moduleDomains = \core\db\Db::getMysql()->rowsNum('SELECT `domainName` FROM `tbl_modules_domains` WHERE `moduleId` = ?d', array($domainId));
		$moduleDomainsArray = array();
		foreach ($moduleDomains as $domain)
			$moduleDomainsArray[] = $domain[0];
		return $moduleDomainsArray;
	}

	protected function getDomainsByModuleAlias($moduleAlias)
	{
		$moduleId = $this->setObject($this->objectsClass)->modelObject->getIdByAlias($moduleAlias);
		return $this->getDomainsByDomainId($moduleId);
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('moduleDomain_delete');
		if (isset($this->getREQUEST()[0]))
			$moduleDomainId = (int)$this->getREQUEST()[0];

		if (!empty($moduleDomainId)) {
			$moduleDomain = $this->getObject($this->objectClass, $moduleDomainId);
			$this->ajaxResponse($moduleDomain->remove());
		}
	}

	protected function getModules()
	{
		$this->setObject($this->objectsClass);
		return $this->modelObject->setOrderBy('`priority` ASC');
	}

}
