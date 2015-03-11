<?php
namespace controllers\base;
class ClientsBaseController extends Controller
{
	use \core\traits\controllers\Authorization;
	protected $permissibleActions = array(
		'add',
		'edit',
		'editLogin',
		'editAction'
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\clients\lib\ClientConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
	}

	protected function add()
	{
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject
						->setLogin($this->getPOST()['login'])
						->setPassword($this->getPOST()['password'], $this->getPOST()['passwordConfirm'])
						->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->setClient();
		if($this->modelObject->getLogin() != $this->getPOST()['login'])
			$this->editLogin();
		$this->editAction();
	}

	protected function editAction($fields = null, $rules = null)
	{
		return $this->ajax($this->modelObject->edit($this->getPOST(), $fields, $rules), 'ajax');
	}

	private function setClient()
	{
		$client = new \modules\clients\lib\Client((int)$this->getPOST()['id']);
		$this->setObject($client);
	}

	protected function editLogin()
	{
		$this->setClient();
		 return $this->modelObject->editLogin($this->getPOST()['login']);
	}

	protected function editPassword()
	{
		$this->setClient();
		$this->modelObject->editPassword($this->getPOST()['password'],$this->getPOST()['password']);
	}
}