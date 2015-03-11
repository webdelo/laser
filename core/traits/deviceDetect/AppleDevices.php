<?php
// Requires: \core\traits\ObjectPool
namespace core\traits\deviceDetect;
trait AppleDevices
{
	protected function isiPad()
	{
		return !!strpos($this->getSERVER()['HTTP_USER_AGENT'],'iPad');
	}

	protected function isiPhone()
	{
		return !!strpos($this->getSERVER()['HTTP_USER_AGENT'],'iPhone');
	}

	protected function isAppleGadget()
	{
		return ($this->isiPad() || $this->isiPhone());
	}
}