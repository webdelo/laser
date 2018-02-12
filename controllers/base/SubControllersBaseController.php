<?php
namespace controllers\base;
class SubControllersBaseController extends Controller
{
	use \core\traits\controllers\Authorization,
		\core\traits\controllers\RequestLevels,
		\core\traits\RequestHandler,
		\core\traits\controllers\ControllersFolders;

	public function __call($name, $arguments)
	{
		if( $this->getREQUEST()->action )
			return $this->chooseController($name, $arguments);

		return $this->defaultAction();
	}

	protected function chooseController($name, $arguments = null)
	{
		if($this->setAction($name)->isPermissibleAction())
			return $this->callAction($arguments);
		else {
			$this->moveRequestLevel();
			$action = $this->getREQUEST()->action;
			return $this->setControllersFolder($this->getREQUEST()->lastController)
						->getSubController($name)->$action();
		}
	}
}