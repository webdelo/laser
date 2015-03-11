<?php
namespace controllers\admin;
class PartnersAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'add',
		'edit',
		'partner',
		'remove',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\partners\lib\PartnerConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
	}

	protected function defaultAction()
	{
		return $this->partners();
	}

	protected function partners()
	{
		$this->checkUserRightAndBlock('partners');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);

		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);


		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($status))
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($name))
			$this->modelObject->setSubquery('AND `name` LIKE \'%?s%\'', $name);


		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('partners', $this->modelObject)
			->setContent('statuses', $this->modelObject->getStatuses())
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'partners');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('partner_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('partner_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function partner()
	{
		$this->checkUserRightAndBlock('partner');
		$this->useRememberPastPageList();

		$object = new \core\Noop();

		if (isset($this->getREQUEST()[0])) {
			$object = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
		}
		$tabs = array('editPartner' => 'Общая информация');

		$objects = new \modules\partners\lib\Partners();
		$modules = new \modules\modulesDomain\lib\ModulesDomain;

		$this->setContent('object', $object)
			 ->setContent('tabs', $tabs)
			 ->setContent('statuses', $objects->getStatuses())
			 ->setContent('modules', $modules)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'partner');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('partner_delete');
		if (isset($this->getREQUEST()[0]))
			$partnerId = (int)$this->getREQUEST()[0];

		if (!empty($partnerId)) {
			$partner = $this->getObject($this->_config->getObjectClass(), $partnerId);
			$this->ajaxResponse($partner->remove());
		}
	}

}
