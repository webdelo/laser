<?php
namespace core\modules\filesUploaded;
class FileUploadedObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\core\modules\filesUploaded\FileUploadedConfig';

	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}

	public function delete()
	{
		$config = $this->getParentObjectConfig();
		$file = new \core\files\uploader\File(DIR.$config->filesPath.$this->id.'.'.$this->extension);
		$file->delete();
		return ( parent::delete() ) ? (int)$this->id : false ;
	}

	public function getParentObjectConfig()
	{
		return $this->getConfig()->getParentConfig();
	}

	public function getRealPath()
	{
		$config = $this->getParentObjectConfig();
		$config = $config->getParentConfig()->getConfig();
		if(file_exists(DIR.$config->filesPath.$this->id.'.'.$this->extension))
			return '/'.$config->filesPath.$this->id.'.'.$this->extension;
		else
			return 'this file does not exist';
	}

	public function getPath($parentId)
	{
		$config = $this->getParentObjectConfig();
		$config = $config->getParentConfig()->getConfig();
		if(file_exists(DIR.$config->filesPath.$this->id.'.'.$this->extension))
			return '/'.$config->filesUrl.$parentId.'/'.$this->alias.'.'.$this->extension;
		else
			return 'this file does not exist';
	}

	public function getSize()
	{
		return file_exists(DIR.$this->getRealPath()) ? \core\utils\Utils::getDistinctFileSize( filesize(DIR.$this->getRealPath()) ) : 0;
	}

	public function isPrimary()
	{
		return ((int)$this->categoryId === 2);
	}

	public function isBlocked()
	{
		return ((int)$this->statusId === 2);
	}


}