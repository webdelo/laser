<?php
namespace core\traits\controllers;
trait ControllersHandler
{
	protected function getController($controller)
	{
		return \controllers\base\ControllerFactory::getInstance()->$controller;
	}
}