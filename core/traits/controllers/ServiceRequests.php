<?php
// Requires: \core\traits\ControllersHandler
namespace core\traits\controllers;
trait ServiceRequests
{
	protected function redirect404()
	{
		$this->getController('Service')->redirect404();
	}
	
	protected function accessDenied($right)
	{
		$this->getController('Service')->accessDenied($right);
	}
	
	protected function forbidden()
	{
		$this->getController('Service')->forbidden();
	}
}