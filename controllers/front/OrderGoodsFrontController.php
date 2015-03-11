<?php
namespace controllers\front;
class OrderGoodsFrontController extends \controllers\base\Controller
{
	protected $permissibleActions = array(
		'add',
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\orders\orderGoods\lib\OrderGoodConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->redirect404();
	}

	protected function add()
	{
		$post = new \core\ArrayWrapper($_POST);
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($post, $this->modelObject->getConfig()->getObjectFields());
		return $objectId;
	}
}
