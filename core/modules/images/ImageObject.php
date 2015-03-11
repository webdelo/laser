<?php
namespace core\modules\images;
class ImageObject extends \core\modules\base\ModuleObject
{
	const FOCUS = true;

	protected $configClass = '\core\modules\images\ImageConfig';

	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}

	public function delete()
	{
		$id = $this->id;
		$path = $this->getPath();
		if ( parent::delete() ){
			$file = new \core\files\uploader\File($path);
			$file->delete();
			$this->clearImageCache();
			return (int)$id;
		}
		return false;
	}

	public function edit( $data = null, $fields = array(), $rules = array() )
	{
		$this->clearImageCache();
		return parent::edit($data, $fields, $rules);
	}

	public function getParentObjectConfig()
	{
		return $this->getConfig()->getParentConfig();
	}

	public function getPath()
	{
		$config = $this->getParentObjectConfig();
		$config = $config->getParentConfig()->getConfig();
		if(file_exists(DIR.$config->imagesPath.$this->id.'.'.$this->extension))
			return DIR.$config->imagesPath.$this->id.'.'.$this->extension;
		else
			return NO_IMAGE_FILE_PATH;
	}

	public function getImage($resize = '0x0', $watermark = null)
	{
		return $this->baseImageResize($resize, $watermark);
	}

	private function baseImageResize($resize, $watermark, $focusMode = false)
	{
		return $this->checkResizeString($resize)->getCacher()->getImagePath($this, $resize, $watermark, $focusMode);
	}

	private function checkResizeString($resize)
	{
		if ($resize) {
			$values = explode('x', $resize);
			if (sizeof($values) == 2)
				return $this;
			else
				throw new \Exception('Resize arguments are not valid!');
		}
		return $this;
	}

	private function getCacher()
	{
		return \core\ObjectPool::getInstance()->getObject('\core\images\resize\Cache');
	}

	public function getFocusImage($resize = '0x0', $watermark = null)
	{
		return $this->baseImageResize($resize, $watermark, self::FOCUS);
	}

	public function getUserImage($resolution = '0x0', $watermark = null)
	{
		return $this->checkResizeString($resolution)->getCacher()->getUserImagePath($this, $resolution, $watermark);
	}

	private function clearImageCache()
	{
		$this->getCacher()->clearImageFiles($this);
		return $this;
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