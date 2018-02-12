<?php
namespace core\traits\controllers;
trait DomainsSubControllersRedirects
{
	protected $controllersFolder = '';

	private $controllers = array();

	public function __call($name, $arguments)
	{
		return call_user_func_array(array($this->getControllerByDomainAlias($this->getCurrentDomainAlias()), $name), $arguments);
	}

	protected function setControllersFolder($folder)
	{
		$this->controllersFolder = $folder;
		return $this;
	}

	private function getControllerByDomainAlias($domainAlias)
	{
		if (empty($this->controllers[$domainAlias])){
			$controllerClass = $this->getControllerNameByDomainAlias($domainAlias);
			$this->controllers[$domainAlias] = new $controllerClass();
		}
		return $this->controllers[$domainAlias];
	}

	private function getControllerNameByDomainAlias($domainAlias)
	{
		$classNameArray = explode('\\', get_class($this));
		$controllerClassName = array_pop($classNameArray);
		$subControllerClass = '\\'.implode('\\', $classNameArray).'\\'.$this->controllersFolder.$this->getDevelopersDomainAlias($domainAlias).$controllerClassName;
		return $subControllerClass;
	}

	public function __get($name)
	{
		return $this->getControllerByDomainAlias($this->getCurrentDomainAlias())->$name;
	}
}