<?php
namespace controllers\admin;
class LocationRegionsAdminController extends \controllers\base\Controller
{
	use \core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'locationRegions',
		'add',
		'edit',
		'locationRegion',
		'remove',

		/* Start: List Trait Methods*/
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\locations\components\regions\lib\LocationsRegionConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->locationRegions();
	}

	protected function locationRegions()
	{
		$this->checkUserRightAndBlock('locationRegions');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$countryId = (empty($this->getGET()['countryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['countryId']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($countryId))
			$this->modelObject->setSubquery('AND `countryId` = ?d', $countryId);

		$this->modelObject->setOrderBy('`id` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'locationRegions');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('locationRegion_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('locationRegion_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function locationRegion()
	{
		$this->checkUserRightAndBlock('locationRegion');
		$this->useRememberPastPageList();

		$locationRegion = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$locationRegion = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editLocationRegion' => 'Параметры');

		$locationRegions = new $this->objectsClass;
		$this->setContent('locationRegion', $locationRegion)
			 ->setContent('tabs', $tabs)
			 ->setContent('locationRegions', $locationRegions)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'locationRegion');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('locationRegion_delete');
		if (isset($this->getREQUEST()[0]))
			$locationRegionId = (int)$this->getREQUEST()[0];

		if (!empty($locationRegionId)) {
			$locationRegion = $this->getObject($this->objectClass, $locationRegionId);
			$this->ajaxResponse($locationRegion->remove());
		}
	}
}
