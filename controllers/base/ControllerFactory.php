<?php
namespace controllers\base;
class ControllerFactory
{
	use	\core\traits\ObjectPool,
		\core\traits\RequestHandler;

	private $controllersArray = array();
	private $controllerName;
	private $controllerType;
	private $config;

	private $controller;
	private $mainController;
	private $controllerTypes = array('Front', 'Admin');

	static protected $instance = null;

	private static function init()
	{
		self::$instance = new ControllerFactory();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	private function __construct()
	{
		$this->setConfig();
	}

	public function setConfig($config = null)
	{
		if (!$config || !is_array($config))
			$config = $this->getDefaultConfig();
		$this->config = $config;
		return $this;
	}

	private function getDefaultConfig()
	{
		return array(
			'defaultFrontController' => 'article',
			'defaultAdminController' => 'articles',
		);
	}

	public function getMainController()
	{
		return $this->mainController;
	}

	public function __get($controllerName)
	{
		return $this->getController($controllerName);
	}

	public function getController($controllerName)
	{
		$this->setControllerType();
		$this->setControllerName($controllerName);
		$controllerFileName = $this->getControllerFileName($this->controllerName);
		return (isset($this->controllersArray[$controllerFileName])) ? $this->controllersArray[$controllerFileName] : $this->setController();
	}

	private function setControllerType()
	{
		$this->controllerType = ucfirst(TYPE);
	}

	private function setControllerName($controllerName)
	{
		$propertyName = 'default'.$this->controllerType.'Controller';
		$this->controllerName = ($controllerName) ? $controllerName : $this->config[$propertyName];
		$this->controllerName = ucfirst($this->controllerName);
		$this->setMainControllerIfEmpty();
	}

	private function setMainControllerIfEmpty()
	{
		if (!$this->mainController)
			$this->mainController = $this->controllerName;
	}

	private function getControllerFileName($name = null)
	{
		$name = (empty($name)) ? $this->controllerName : $name;
		return 'controllers\\'.strtolower($this->controllerType).'\\'.$name.$this->controllerType.'Controller';
	}

	private function setController()
	{
		$this->checkControllerType();
		if (!$this->checkController()) {
			$this->requestRebuildWithoutController();
			$this->setDefaultController();
		}
		$this->setInstanceController();
		$this->addControllerToArray();
		return $this->controller;
	}

	private function checkControllerType()
	{
		if (!in_array($this->controllerType, $this->controllerTypes))
			throw new \Exception('Controller Type was not found in ControllerTypes List!');
	}

	public function checkController() {
		return file_exists(DIR.'controllers/'.strtolower($this->controllerType).'/'.$this->controllerName.$this->controllerType.'Controller.php');
	}

	private function requestRebuildWithoutController() {
		\core\url\UrlDecoder::getInstance()->requestRebuildWithoutController();
	}

	private function setDefaultController()
	{
		$this->setControllerName($this->getDefaultControllerName());
		$this->getREQUEST()['controller'] = $this->getDefaultControllerName();
	}

	private function getDefaultControllerName()
	{
//		$propertyName = 'default'.$this->controllerType.'Controller';
//		return $this->config[$propertyName];
		return 'Service';
	}

	private function setInstanceController()
	{
		$this->controller = $this->instanceController();
	}

	private function instanceController()
	{
		$controllerName = $this->getControllerFileName();
		return $this->getObject($controllerName);
	}

	private function addControllerToArray()
	{
		$this->controllersArray[get_class($this->controller)] = $this->controller;
	}
}