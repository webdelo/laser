<?php
namespace core\traits;
trait ConfigValidation
{
	public function setConfig($config)
	{
		$this->config = $config;
		return $this->checkConfig();
	}

	private function checkConfig()
	{
		if (!is_array($this->config) || empty($this->config))
			throw new \Exception('Was not passed Configuration Array in class '.get_class($this).'!');
		foreach ($this->config as $key => $value){
            $chkMethod = '_checkConfigKey'.ucfirst($key);
            if(!method_exists($this, $chkMethod)) {
                throw new \Exception('Not find method for check config-key "'.$key.'" in '.get_class($this).'!');
            }
            $this->$chkMethod($value);
		}
		return $this;
	}

	private function _checkNotEmpty($key, $value)
	{
		if (empty($value))
			 throw new \Exception('Config-key "'.$key.'" can\'t be empty in class '.get_class($this).'!');
		return true;
	}
}