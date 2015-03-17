<?php
namespace core\traits\adapters;
trait User
{
	use \core\traits\controllers\Authorization;

	public function _adaptUser($key)
	{
		if (isset($this->data[$key]))
		    $this->data[$key] = (int)$this->data[$key];
		else
		    $this->data[$key] = (int)$this->getAuthorizatedUser()->id;
	}

	public function _adaptPassword($key)
	{
		if (isset($this->data[$key]))
		    $this->data[$key] = strtolower(md5($this->data[$key])) ;
	}
}