<?php
namespace controllers\admin;
class MeasuresAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Categories,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'measures',
		'add',
		'edit',
		'measure',
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
		$this->_config = new \modules\measures\lib\MeasureConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->measures();
	}

	protected function measures ()
	{
		$this->checkUserRightAndBlock('measures');
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

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'measures');
	}

	public function add()
	{
		$this->checkUserRightAndBlock('measure_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('measure_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function measure()
	{
		$this->checkUserRightAndBlock('measure');
		$this->useRememberPastPageList();

		$measure = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$measure = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editMeasure' => 'Параметры');

		$measures = new $this->objectsClass;
		$this->setContent('measure', $measure)
			 ->setContent('tabs', $tabs)
			 ->setContent('measures', $measures)
			 ->setContent('statuses', $measures->getStatuses())
			 ->setContent('mainCategories', $measures->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'measure');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('measure_delete');
		if (isset($this->getREQUEST()[0]))
			$measureId = (int)$this->getREQUEST()[0];

		if (!empty($measureId)) {
			$measure = $this->getObject($this->objectClass, $measureId);
			$this->ajaxResponse($measure->remove());
		}
	}
}
?>
