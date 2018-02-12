<?php
namespace controllers\admin;
class ServiceAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\ControllersHandler,
		\core\traits\controllers\Templates;

	public function __call($actionName, $arguments)
	{
		if (method_exists($this, $_REQUEST['action']))
			return call_user_func_array(array($this, $actionName), $arguments);
		else {
			$defaultControllerName = \core\Configurator::getInstance()->controllers->defaultAdminController;
			return $this->getController($defaultControllerName)->$_REQUEST['action']();
		}
	}

	public function redirect404()
	{
		header("HTTP/1.0 404 Not Found");
		$this->includeTemplate('404');
	}

	protected function accessDenied($right)
	{
		$this->includeTemplate(TEMPLATES_ADMIN.'services/accessDenied');
	}

	protected function forbidden()
	{
		echo 'forbidden';
	}
}