<?php
namespace controllers\admin;
class LocationCitiesAdminController extends \controllers\base\Controller
{
	use \core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;



	protected $permissibleActions = array(
		'locationCities',
		'add',
		'edit',
		'locationCity',
		'remove',

		/* Start: List Trait Methods*/
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

		'getRegionSelect',
		'ajaxGetRegionSelect'
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\locations\components\cities\lib\LocationsCityConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->locationCities();
	}

	protected function locationCities()
	{
		$this->checkUserRightAndBlock('locationCities');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$regionId = (empty($this->getGET()['regionId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['regionId']);
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

		if (!empty($regionId))
			$this->modelObject->setSubquery('AND `regionId` = ?d', $regionId);

		if (!empty($countryId))
			$this->modelObject->setSubquery('AND `countryId` = ?d', $countryId);

		$this->modelObject->setOrderBy('`id` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'locationCities');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('locationCity_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('locationCity_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function locationCity()
	{
		$this->checkUserRightAndBlock('locationCity');
		$this->useRememberPastPageList();

		$locationCity = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$locationCity = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editLocationCity' => 'Параметры');

		$locationCities = new $this->objectsClass;
		$this->setContent('locationCity', $locationCity)
			 ->setContent('tabs', $tabs)
			 ->setContent('locationCities', $locationCities)
			->setContent('bulgaryCountry', $this->getObject('\modules\locations\components\countries\lib\LocationsCountry', $this->getObject('\modules\locations\components\countries\lib\LocationsCountryConfig')->getBulgaryCountryId()))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'locationCity');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('locationCity_delete');
		if (isset($this->getREQUEST()[0]))
			$locationCityId = (int)$this->getREQUEST()[0];

		if (!empty($locationCityId)) {
			$locationCity = $this->getObject($this->objectClass, $locationCityId);
			$this->ajaxResponse($locationCity->remove());
		}
	}

	protected function getRegionSelect($countryId = null)
	{
		$countryId = isset($countryId) ? $countryId : $this->getGet()['countryId'];
		$regions = new \modules\locations\components\regions\lib\LocationsRegions();
		$this->setContent('regions', $regions->getRegionsByCountryId($countryId))
			->includeTemplate($this->_config->getAdminTemplateDir().'locationRegionsSelect');
	}

	protected function ajaxGetRegionSelect()
	{
		return $this->getRegionSelect($this->getGet()['countryId']);
	}
}
