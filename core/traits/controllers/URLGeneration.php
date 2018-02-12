<?php
namespace core\traits\controllers;
trait URLGeneration
{
	private $config;
	
	private function getUrlConfig()
	{
		if (empty($this->config)) {
			$urlEncodeConfig = \core\Configurator::getInstance()->getArrayByKey('url');
			$urlEncodeCurrents = array(
				'currents'=>array(
							'controller'=>strtolower($this->getMainController()),
							'lang'=>$this->getLang(),
							'part'=>$this->getPart(),
							)
				);
			$this->config = array_merge($urlEncodeConfig, $urlEncodeCurrents);
		}
		return $this->config;
		\core\url\UrlEncoder::getInstance()->setConfig($urlEncodeConfig);
	}
	
	private function getUrlEncoder()
	{
		return \core\url\UrlEncoder::getInstance()->setConfig($this->getUrlConfig());
	}

	protected function setUrlController($name) 
	{
		$this->getUrlEncoder()->setCurrentController($name);
		return $this;
	}

	protected function setUrlPart($name) {
		$this->getUrlEncoder()->setCurrentPart($name);
		return $this;
	}

	protected function setUrlLang($name) {
		$this->getUrlEncoder()->setCurrentLang($name);
		return $this;
	}

	protected function setUrlProtocol($name) {
		$this->getUrlEncoder()->setProtocol($name);
		return $this;
	}

	protected function printUrl($action, $data=null) {
		$this->getUrlEncoder()->printUrl($action, $data);
	}
}