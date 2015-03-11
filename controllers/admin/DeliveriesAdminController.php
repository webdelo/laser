<?php
namespace controllers\admin;
class DeliveriesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\core\traits\controllers\Categories,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const domainsTable = 'tbl_modules_domains';

	protected $permissibleActions = array(
		'deliveries',
		'add',
		'edit',
		'delivery',
		'remove',

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
		$this->_config = new \modules\deliveries\lib\DeliveryConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->deliveries();
	}

	protected function deliveries()
	{
		$this->checkUserRightAndBlock('deliveries');
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

		$this->setContent('deliveries', $this->modelObject)
			->setContent('statuses', $this->modelObject->getStatuses())
			->setContent('mainCategories', $this->modelObject->getMainCategories())
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'deliveries');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('delivery_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('delivery_edit');
		$object = $this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id']);
		$edit = $this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$object->ajax($edit);
	}

	protected function delivery()
	{
		$this->checkUserRightAndBlock('delivery');
		$this->useRememberPastPageList();

		$delivery = new \core\Noop();

		if (isset($this->getREQUEST()[0])) {
			$delivery = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
		}
		$tabs = array('editDelivery' => 'Параметры');

		$deliveries = new $this->objectsClass;
		$this->setContent('delivery', $delivery)
			->setContent('tabs', $tabs)
			->setContent('deliveries', $deliveries)
			->setContent('statuses', $deliveries->getStatuses())
			->setContent('mainCategories', $deliveries->getMainCategories(1))
			->includeTemplate($this->_config->getAdminTemplateDir().'delivery');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('delivery_delete');
		if (isset($this->getREQUEST()[0]))
			$moduleDomainId = (int)$this->getREQUEST()[0];

		if (!empty($moduleDomainId)) {
			$moduleDomain = $this->getObject($this->objectClass, $moduleDomainId);
			$this->ajaxResponse($moduleDomain->remove());
		}
	}

	public function getDeliveryByCategoryId()
	{	
		$deliveries = array();
		foreach($this->getDeliveryListByCategoryId((int)$this->getPOST()->deliveryCategoryId) as $region) {
			$deliveries[] = array(
				'value' =>$region->id,
				'name'  =>$region->name
			);
		}
		echo json_encode($deliveries);
	}
	
	public function getDeliveryListByCategoryId($id)
	{
		$deliveries = new $this->objectsClass;
		$deliveries->setSubquery('AND `categoryId` = ?d', (int)$id);
		
		return $deliveries;
	}
}
