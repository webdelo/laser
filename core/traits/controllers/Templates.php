<?php
namespace core\traits\controllers;
trait Templates
{
	protected $content;

	protected function includeTemplate($tplName)
	{
		// define content variables
		if ($this->content)
			foreach ($this->content as $key => $value)
				$$key = $value;

		include $this->getTemplate($tplName) ;
	}

	protected function getTemplate($tplName)
	{
		$method = 'getPathTo'.ucfirst(TYPE).'Template';
		return $this->$method($tplName);
	}

	private function getPathToFrontTemplate ($tplName)
	{
		return DIR.'templates/'.$this->getCurrentDomainAlias().'/'.$this->getCurrentLang().'/'.$tplName.'.tpl';
	}

	private function getPathToAdminTemplate ($tplName)
	{
		$path = $tplName.'.tpl';

		if (file_exists($path) ) {
			return $path;
		}
		
		if (property_exists($this,'_config'))
			if (file_exists(DIR.$this->_config->templates.$path)) {
				return DIR.$this->_config->templates.$path;
			}

		$path = TEMPLATES_ADMIN.$tplName.'.tpl';
		if (file_exists($path) ) {
			return $path;
		}
	}

	protected function setContent($varName, $content)
	{
		$this->content[$varName] = $content;
		return $this;
	}
}