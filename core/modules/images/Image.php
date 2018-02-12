<?php
namespace core\modules\images;
class Image extends \core\modules\base\ModuleObject
{
	use	\core\traits\ObjectPool,
		\core\modules\statuses\StatusTraitDecorator,
		\core\modules\categories\CategoryTraitDecorator;

	const FOCUS = true;

	protected $configClass = '\core\modules\images\ImageConfig';
	private $_siblings;
	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}

	public function delete()
	{
		$id        = $this->id;
		$path      = $this->getPath();
		$isPrimary = $this->isPrimary();
		
		if ( parent::delete() ) {
			if ( $isPrimary ) {
				if ( $this->_getSiblings()->current() instanceof Image ) {
					$this->_getSiblings()->current()->setPrimary();
				}
			}
			if ( !$this->isNoImage() ) {
				$file = new \core\files\uploader\File($path);
				$file->delete();
			}
			$this->clearImageCache();
			return (int)$id;
		}
		return false;
	}
	
	private function isNoImage()
	{
		return (NO_IMAGE_FILE_PATH === $this->getPath());
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

	public function isPrimary()
	{
		return ((int)$this->categoryId === ImageConfig::PRIMARY_CATEGORY_ID);
	}

	public function isBlocked()
	{
		return ((int)$this->statusId === ImageConfig::STATUS_BLOCKED);
	}

	private function clearImageCache()
	{
		$this->getCacher()->clearImageFiles($this);
		return $this;
	}
	
	public function defaultSetPrimary()
	{
		if ( $this->isFirstImage() ) {
			return $this->setPrimary();
		}
		return false;
	}
	
	public function setPrimary() 
	{
		$this->resetPrimary();
		return $this->editField(ImageConfig::PRIMARY_CATEGORY_ID, 'categoryId');
	}
	
	public function resetPrimary()
	{
		$query     = ' UPDATE `'.$this->mainTable().'` SET `categoryId`=?d WHERE `objectId`=?d AND `categoryId`=?d ';
		$data = array( ImageConfig::SECONDARY_CATEGORY_ID, $this->getObjectId(), ImageConfig::PRIMARY_CATEGORY_ID );
		
		$result = \core\db\Db::getMysql()->query($query, $data);
		if ( !$result ) {
			throw new \Exception('Image::setPrimary() Error! Can\'t reset prev primary images ');
		}
		return true;
	}
	
	private function _getSiblings()
	{
		if ( !$this->_siblings ) {
			$this->_siblings = new Images($this->getParentObjectConfig());
			$this->_siblings->setSubquery(' AND ( `objectId`=?d AND `id`!=?d ) ', $this->getObjectId(), $this->id);
		}
		return $this->_siblings;
	}
	
	public function isFirstImage()
	{
		$result = \core\db\Db::getMysql()->rowsNum('SELECT `id` FROM `'.$this->mainTable().'` WHERE `objectId`=?d ', array($this->getObjectId()));	
		return ( sizeof($result) === 1 );
	}
	
	public function getObjectId()
	{
		$this->loadObjectInfo();
		return $this->objectInfo['objectId'];
	}
}