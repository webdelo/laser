<?php
namespace core\files\uploader;
class File
{
	private $permissions = array('777', '775');
	private $file = '';
	private $info = '';
	
	public function __construct($file)
	{
		$this->checkFileExists($file)
			 ->setFile($file)
			 ->setFileInfo();
	}	
	
	private function checkFileExists ($file)
	{
		if	( !file_exists($file) ) {
			throw new \Exception ('File '.$file.' does not exists!');
		}
		return $this;
	}
	
	private function setFile($file)
	{
		$this->file = $file;
		return $this;
	}
	
	private function setFileInfo()
	{
		$this->info = pathinfo($this->file);
		return $this;
	}

	public function __get($name) {
		return $this->info[$name];
	}
	
	public function getFilePath()
	{
		return $this->dirname.'/'.$this->basename;
	}
	
	public function getFileContent()
	{
		return file_get_contents($this->getFilePath());
	}
	
	public function copy($dest)
	{
		$this->checkPermissions($dest);
		
		return copy($this->file, $dest);
	}
	
	public function move($dest)
	{
		$this->rename($dest);
	}
	
	public function rename($newName)
	{
		$this->checkPermissions($newName);
		
		return rename($this->file, $newName);
	}
	
	public function delete()
	{	
		return unlink($this->file);
	}
	
	private function checkPermissions($file)
	{
		$this->checkFilePermissions($file)
			 ->checkDirectoryPermissions($file);
		
		return $this;
	}
	
	private function checkFilePermissions ($file)
	{
		if (file_exists($file)) {
			$permissions = $this->getDecodedFilePermissions($file);

			if (!in_array($permissions, $this->permissions)) {
				throw new \Exception ('File '.$file.' don\'t have needed permissions!');
			}
		}
		
		return $this;
	}
	
	private function checkDirectoryPermissions ($file)
	{
		$directory = $this->getDirectoryName($file);
		$permissions = $this->getDecodedFilePermissions($directory);
		
		if (!in_array($permissions, $this->permissions)) {
			throw new \Exception ('Directory '.$directory.' don\'t have needed permissions!');
		}
		return $this;
	}
	
	private function getDirectoryName ($file) {
		$file = pathinfo($file);
		return $file['dirname'];
	}
	
	private function getDecodedFilePermissions ($file) {
		return substr(sprintf('%o', fileperms($file)), -3);
	}
}