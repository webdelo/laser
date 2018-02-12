<?php
namespace core\traits\controllers;
trait ControllersFolders
{
	protected $controllersFolder = '';

	private $controllers = array();
	
	public function getSubController($name)
	{
		$exploded               = explode('\\', get_class($this));
		$controllersFolder      = array_shift($exploded);
		$controllersPart        = array_shift($exploded);
		$settedControllerFolder = $this->controllersFolder ? '\\'.$this->controllersFolder : '' ;
		$controllerName = $controllersFolder.'\\'.$controllersPart.$settedControllerFolder.'\\'.ucfirst($name).ucfirst($controllersPart).'Controller';
		
		if ( !file_exists(DIR.str_replace('\\', '/', $controllerName).'.php') ) {
			return $this->redirect404();
		}
		
		return new $controllerName;
	}

	protected function setControllersFolder($folder)
	{
		$this->controllersFolder = $folder;
		return $this;
	}
}