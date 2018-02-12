<?php
namespace core\files\includers;
class JSincluder extends FileIncluder
{
	public function __construct($file)
	{
		$this->setConfig(array(
			'ext'        => '.js',
			'filePathIn' => DIR.'js/tmp/',
		));
		parent::__construct($file);
	}
	
	protected function setHeaders()
	{
		header("Content-type: text/javascript");
		header("Cache-Control: no-cache, must-revalidate");
	}
}
