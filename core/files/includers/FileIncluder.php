<?php
namespace core\files\includers;
abstract class FileIncluder
{
	protected $config        = array();
	protected $file          = '';
	protected $fileContent   = '';
	
	abstract protected function setHeaders();
	
	protected function __construct($file)
	{
		$this->setFile($file)
			 ->setFileContent()
			 ->printFile();
	}
	
	protected function setFile ($file)
	{
		$this->file = $file;
		return $this;
	}
	
	protected function setFileContent ()
	{
		ob_start();
		include($this->config['filePathIn'].$this->file.$this->config['ext']);
		$this->fileContent = ob_get_contents();
		ob_end_clean();

		return $this;
	}

	protected function setConfig($config)
	{
		if ($config)
			$this->config = $config;
	}

	public function printFile()
	{
		$this->setHeaders();
		echo $this->fileContent;
	}
}