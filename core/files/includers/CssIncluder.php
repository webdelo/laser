<?php
namespace core\files\includers;
class CSSincluder extends FileIncluder
{
	public function __construct($file)
	{
		$this->setConfig(array(
			'ext'        => '.css',
			'filePathIn' => DIR.'css/tmp/',
		));		
		parent::__construct($file);
	}
	
	protected function setHeaders()
	{
		header("Content-type: text/css");
		header("Cache-Control: no-cache, must-revalidate");
	}	
}
