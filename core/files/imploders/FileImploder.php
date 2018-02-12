<?php
namespace core\files\imploders;
abstract class FileImploder
{
	protected $files        = array();
	protected $fileContents = '';
	protected $finalContent = '';
	protected $finalFileName    = '';
	protected $newFile    = '';

	protected function setConfig($config)
	{
		if ($config)
			$this->config = $config;
	}
	
	public function printf($param=null)
	{
		if($param=='compact')
			$this->config['compress'] = true;
		
			$this->setFileContents()
				 ->compressFiles()
				 ->implodeFiles()
				 ->createFile()
				 ->getTag();
	}
	
	public function tagsPrint($param=null)
	{
			foreach ($this->files as $key=>$file)
				$this->getTag($file[0], (isset($file[1])) ? $file[1] : $this->config['filePathIn']);
	}
	
	public function add()
	{
		$this->files[] = func_get_args();
		return $this;
	}
	
	public function setFileContents()
	{
		foreach ($this->files as $file) {
			$filepath = (!isset($file[1])) ? $this->config['filePathIn'].$file[0] : DIR.$file[1].$file[0];			
			ob_start();
			include $filepath;
			$this->fileContents[] = ob_get_contents();
			ob_end_clean();
		}
		return $this;
	}

	protected function compressFiles()
	{
		if ($this->config['compress']) {
			foreach ( $this->fileContents as $key => $content ) {
				$this->fileContents[$key] = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
			}
		}
		return $this;
	}

	protected function implodeFiles()
	{
		$this->finalContent = implode("\n\r", $this->fileContents);
		return $this;
	}

	private function createFile () 
	{
		if ($this->config['cache']) {
			$this->setFileName()
				 ->setPathNewFile()
				 ->removeOldFile()
				 ->createFileAction();
		}
		return $this;
	}
	
	private function setPathNewFile()
	{
		$this->newFile = $this->config['filePathOut'].$this->finalFileName.$this->config['ext'];
		return $this;
	}
	
	private function fileExists () {
		return file_exists($this->newFile);
	}

	private function removeOldFile()
	{
		if( $this->fileExists() ) {
			unlink($this->newFile);
		}
		return $this;
	}
	
	private function createFileAction()
	{
		$fp = fopen($this->newFile, 'w+');
		fwrite($fp, $this->finalContent);
		fclose ($fp);
			
		return $this;
	}
	
	private function setFileName()
	{
		foreach ($this->files as $file)
			$files[] = array_shift($file);
		
		$this->finalFileName = md5(implode('', $files));
		
		return $this;
	}
}