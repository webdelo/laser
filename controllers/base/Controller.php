<?php
namespace controllers\base;
abstract class Controller
{
	use	\core\traits\controllers\Ajax,
		\core\traits\controllers\ServiceRequests,
		\core\traits\RequestHandler,
		\core\traits\Errors,
		\core\traits\ObjectPool;

	protected $action;
	protected $permissibleActions = array();

	public function __construct()
	{
		$this->setAction($this->getREQUEST()['action']);
	}

	public function __call($name, $arguments)
	{
		if (empty($name))
			return $this->defaultAction();
		elseif ($this->setAction($name)->isPermissibleAction())
			return $this->callAction($arguments);
		else
			return $this->redirect404();
	}

	protected function defaultAction()
	{
		$this->redirect404();
	}

	protected function setAction($action)
	{
		$this->action = $action;
		return $this;
	}

	protected function actionExists($actionName = null)
	{
		$actionName = isset($actionName) ? $actionName : $this->action;
		return method_exists($this, $actionName);
	}

	protected function isPermissibleAction($actionName = null)
	{
		$actionName = isset($actionName) ? $actionName : $this->action;
		return $this->actionExists($actionName) ? in_array($actionName, $this->permissibleActions) : false;
	}

	protected function callAction($arguments, $actionName = null)
	{
		$actionName = isset($actionName) ? $actionName : $this->action;
		return call_user_func_array(array($this, $actionName), $arguments);
	}
}