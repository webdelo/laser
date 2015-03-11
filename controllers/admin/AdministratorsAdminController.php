<?php
namespace controllers\admin;
class AdministratorsAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'administrators',
		'administrator',
		'ajaxGetGroupRightsById',
		'add',
		'edit',
		'editPassword',
		'remove',
		'groups',
		'group',
		'editGroup',
		'addGroup',
		'removeGroup'
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\administrators\lib\AdministratorConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->administrators();
	}

	protected function administrators ()
	{
		$this->checkUserRightAndBlock('administrators');
		$this->setContent('administrators', new $this->objectsClass)
			 ->includeTemplate('administrators');
	}

	protected function administrator ()
	{
		$this->checkUserRightAndBlock('administrator');
		$administrator = new \core\Noop();
		$userRights = $administrator = new \core\Noop();

		if (isset($this->getREQUEST()[0])) {
			$administrator = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
			$userRights = $administrator->rights;
		}
		$tabs = array('editAdministrator' => 'Данные');
		$administrators = new $this->objectsClass;

		$this->setContent('administrator', $administrator)
			->setContent('administrators', $administrators)
			->setContent('tabs', $tabs)
			->setContent('groups', $administrators->groups)
			->setContent('userRights', $userRights)
			->setContent('tree', $this->getRightsTree($userRights))
			->includeTemplate($this->_config->getAdminTemplateDir().'administrator');
	}

	private function getGroupRightsArrayById ($id)
	{
		$groupObject = new \core\modules\groups\Group($id);
		$rightsArray = $groupObject->rights->getIdArray();
		return $rightsArray;
	}

	protected function ajaxGetGroupRightsById ()
	{
		$group = new \core\modules\groups\Group($this->getGET()['groupId']);
		return $this->ajax($this->getRightsTree($group->rights));
	}

	protected function add ()
	{
		$this->checkUserRightAndBlock('admins_add');
		$post = new \core\ArrayWrapper($this->getPOST());
		$this->setObject($this->objectsClass);
		$result = $this->modelObject
								  ->setPassword($post->password, $post->passwordConfirm)
								->setLogin($post->login)
								  ->add($post);
		if($result == false){
			$errors = $this->modelObject->getErrors();
			$this->resetErrors();
			foreach($errors as $key=>$value)
				$newErrors[$key] = $value;
			return $this->ajaxResponse($newErrors);
		}
		$this->ajax($result,'ajax');
		if ($result) {
			$this->setObject($this->objectClass, $result)->modelObject->rights->add($this->getPOST()['treeList']);
		}
	}

	protected function edit ()
	{
		$this->checkUserRightAndBlock('admins_edit');
		$administrator = new $this->objectClass($this->getPOST()['id']);
		$result = $this->setObject($administrator)->modelObject->edit($this->getPOST());
		$this->ajax($result, 'ajax');
		if ($result)
			$this->modelObject->rights->edit($this->getPOST()['treeList']);
	}

	protected function editPassword()
	{
		$this->checkUserRightAndBlock('admins_edit_password');
		$this->setObject($this->objectClass,$this->getPOST()['id'])
			 ->ajax($this->modelObject->editPassword($this->getPOST()['password'], $this->getPOST()['passwordConfirm']), 'ajax');
	}

	protected function remove ()
	{
		$this->checkUserRightAndBlock('admins_delete');
		if (isset($this->getREQUEST()[0]))
			$administratorId = (int)$this->getREQUEST()[0];

		if (!empty($administratorId)) {
			$administrator = $this->getObject($this->objectClass, $administratorId);
			$this->ajaxResponse($administrator->delete());
		}
	}

	protected function groups ()
	{
		$this->checkUserRightAndBlock('admins_groups_list');
		$groups = new \core\modules\groups\Groups();
		include($this->_config->getAdminTemplateDir().'administratorGroups.tpl');
	}

	protected function group ()
	{
		$this->checkUserRightAndBlock('group');
		$group = new \core\Noop();
		if (isset($this->getREQUEST()[0])){
			$group = new \core\modules\groups\Group($this->getREQUEST()[0]);
		}

		$tabs = array('editGroup' => 'Данные');

		$this->setContent('tabs', $tabs)
			->setContent('object', $group)
			->setContent('tree', $this->getRightsTree($group->rights))
			->includeTemplate($this->_config->getAdminTemplateDir().'administratorGroup');
	}

	protected function editGroup ()
	{
		$this->checkUserRightAndBlock('admins_groups_edit');
		$result = $this->setObject('\core\modules\groups\Group', $this->getPOST()['id'])->modelObject->edit($this->getPOST());
		$this->ajax($result, 'ajax');
		if ($result)
			$this->modelObject->rights->edit($this->getPOST()['treeList']);
	}

	protected function addGroup ()
	{
		$this->checkUserRightAndBlock('addGroup');
		$groupConfig = new \core\modules\groups\GroupConfig();
		$objectId = $this->setObject($groupConfig->getObjectsClass())->modelObject->add($this->getPOST(), $groupConfig->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function removeGroup ()
	{
		$this->checkUserRightAndBlock('removeGroup');
		if (isset($this->getREQUEST()[0]))
			$groupId = (int)$this->getREQUEST()[0];

		if (!empty($groupId)) {
			$group = $this->getObject('\core\modules\groups\Group', $groupId);
			$this->ajaxResponse($group->remove());
		}
	}

}