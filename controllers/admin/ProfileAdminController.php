<?php
namespace controllers\admin;
class ProfileAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'profile',
		'edit',
		'editPassword'
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = $this->getAuthorizatedUser()->getConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->profile();
	}

	protected function profile ()
	{
		$profile = $this->getObject($this->_config->getObjectClass(), $this->getAuthorizatedUser()->id);
		$tabs = array('editProfile' => 'Данные');

		$this->setContent('profile', $profile)
			 ->setContent('tabs', $tabs)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'profile');
	}

	protected function edit ()
	{
		$this->checkUserRightAndBlock('selfProfile_edit');
		$result = $this->setObject($this->getAuthorizatedUser())->modelObject->edit($this->getPOST());
		$this->ajax($result, 'ajax');
	}

	protected function editPassword()
	{
		$this->checkUserRightAndBlock('selfProfile_edit');
		$this->setObject($this->getAuthorizatedUser())
			 ->ajax($this->modelObject->editPassword($this->getPOST()['password'], $this->getPOST()['passwordConfirm']), 'ajax');
	}

}