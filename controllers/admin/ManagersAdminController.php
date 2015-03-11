<?php
namespace controllers\admin;
class ManagersAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager;

	protected $partnersManagersGroupsId = 28;

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\managers\lib\ManagerConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
	}

	public function __call($name, $arguments)
	{
		$this->managers();
	}

	public function managers()
	{
		$this->checkUserRightAndBlock('partners');
		$this->setObject('\modules\partners\lib\Partner',$this->getREQUEST()['action']);
		$this->setContent('managers', $this->modelObject->getManagers())
			 ->setContent('statuses', $this->modelObject->getStatuses())
			 ->setContent('pager', $this->modelObject->getPager())
			 ->includeTemplate($this->_config->getAdminTemplateDir().'managers');
	}

	public function add()
	{
		$this->checkUserRightAndBlock('partners');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->setLogin($this->getPOST()['login'])->setPassword($this->getPOST()['password'],$this->getPOST()['password'])->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if(is_numeric($objectId)){
			$managers = $this->getObject('\modules\managers\lib\Managers');
			$manager = $managers->getObjectById($objectId);
			$this->ajax($manager->partnerId, 'ajax');
		}
		else
			$this->ajax($objectId, 'ajax', true);
	}

	public function edit()
	{
		$this->checkUserRightAndBlock('partners');
		$manager = new \modules\managers\lib\Manager((int)$this->getPOST()['id']);
		$this->ajax($this->setObject($manager)->modelObject->edit($this->getPOST()), 'ajax');
		$this->modelObject->rights->edit($this->getPOST()['treeList']);
		$this->modelObject->editLogin($this->getPOST()['login']);
	}

	public function editPassword()
	{
		$this->checkUserRightAndBlock('partners');
		$this->setObject($this->objectClass,$this->getPOST()['id'])
			 ->ajax($this->modelObject->editPassword($this->getPOST()['password'], $this->getPOST()['passwordConfirm']), 'ajax');
	}

	public function manager()
	{
		$this->checkUserRightAndBlock('partners');
		$refererParts = explode('/',$_SERVER['HTTP_REFERER']);
		$returnAction = $refererParts[count($refererParts)-1];

		$object = new \core\Noop();

		if (isset($this->getREQUEST()[0])) {
			$object = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
			$userRights = $object->rights;
		}
		else
			$userRights = $this->getDefaultManagersRights();

		$tabs = array('editManager' => 'Общая информация');

		$objects = new \modules\managers\lib\Managers();
		$partners = new \modules\partners\lib\Partners();

		$this->setContent('object', $object)
			 ->setContent('tabs', $tabs)
			 ->setContent('statuses', $objects->getStatuses())
			 ->setContent('partners', $partners)
			 ->setContent('returnAction', $returnAction)
			->setContent('userRights', $userRights)
			->setContent('tree', $this->getRightsTree($userRights))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'manager');
	}

	private function getDefaultManagersRights()
	{
		$this->checkUserRightAndBlock('partners');
		$groups = $this->getObject('\core\modules\groups\Groups');
		$partnersManagersGroup = $groups->getObjectById($this->partnersManagersGroupsId);
		return $partnersManagersGroup->rights;
	}

	public function remove()
	{
		$this->checkUserRightAndBlock('partners');
		if (isset($this->getREQUEST()[0]))
			$partnerId = (int)$this->getREQUEST()[0];

		if (!empty($partnerId)) {
			$partner = $this->getObject($this->_config->getObjectClass(), $partnerId);
			$this->ajaxResponse($partner->remove());
		}
	}
}
