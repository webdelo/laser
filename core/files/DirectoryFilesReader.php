<?php
namespace core\files;
class DirectoryFilesReader 
{
	private $path;
	private $filesList;
	
	public function __construct($path)
	{
		$this->setPath($path)
			->checkPath()
			->readFiles();
	}
	
	private function setPath($path)
	{
		$this->path = (string)$path;
		return $this;
	}
	
	private function checkPath()
	{
		if (!file_exists($this->path) || !is_dir($this->path))
			throw new \Exception('Directory "'.$this->path.'" was not found!');
		return $this;
	}
	
	private function readFiles()
	{
		if ($handle = opendir($this->path)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != ".." && !is_dir($this->path.'/'.$entry)) {
					$this->filesList[] = $entry;
				}
			}
			closedir($handle);
		}
		return $this;
	}
	
	public function getFilesList()
	{
		return $this->filesList;
	}
}
