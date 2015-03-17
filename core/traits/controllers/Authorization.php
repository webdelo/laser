<?php
// Requires: \core\traits\controllers\ControllersHandler
namespace core\traits\controllers;
trait Authorization
{
	use \core\traits\controllers\ControllersHandler;

	protected function getAuthorizatedUser()
	{
		return $this->getController('Authorization')->authorizatedUser();
	}

	protected function isAuthorisatedUserAnManager()
	{
		return $this->getAuthorizatedUser()->getGroup() == '\modules\managers\lib\Manager';
	}

	public function isGuest()
	{
		return $this->getAuthorizatedUser()->getGroup() == 'Guests';
	}

	protected function getAuthorizatedUserId()
	{
		$id = $this->getAuthorizatedUser()->id;
		return $id ? $id : 0;
	}
}