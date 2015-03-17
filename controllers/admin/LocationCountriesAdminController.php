<?php
namespace controllers\admin;
class LocationCountriesAdminController extends \controllers\base\Controller
{
	use \core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'locationCountries',
		'add',
		'edit',
		'locationCountry',
		'remove',

		/* Start: List Trait Methods*/
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\locations\components\countries\lib\LocationsCountryConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->locationCountries();
	}

	protected function locationCountries()
	{
		$this->checkUserRightAndBlock('locationCountries');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		$this->modelObject->setOrderBy('`id` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'locationCountries');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('locationCountry_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('locationCountry_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function locationCountry()
	{
		$this->checkUserRightAndBlock('locationCountry');
		$this->useRememberPastPageList();

		$locationCountry = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$locationCountry = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editLocationCountry' => 'Параметры');

		$locationCountries = new $this->objectsClass;
		$this->setContent('locationCountry', $locationCountry)
			 ->setContent('tabs', $tabs)
			 ->setContent('locationCountries', $locationCountries)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'locationCountry');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('locationCountry_delete');
		if (isset($this->getREQUEST()[0]))
			$locationCountryId = (int)$this->getREQUEST()[0];

		if (!empty($locationCountryId)) {
			$locationCountry = $this->getObject($this->objectClass, $locationCountryId);
			$this->ajaxResponse($locationCountry->remove());
		}
	}
}
