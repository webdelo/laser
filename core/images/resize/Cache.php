<?php
namespace core\images\resize;
class Cache
{
    const CACHE_DIR = '/cache';

	private $imageResizer;

	private $_image;
	private $_watermark;
	private $_focus;

	private $_alias;
	private $_extension;
	private $_origFileName;
	private $_imageUrl;
	private $_imagePath;
	private $_imageDirPath;
	private $_imageFullPath;
	private $_imageFileName;

	private $_width;
	private $_heigth;


	public function __construct()
	{
		$this->setImageResizer();
	}

	private function setImageResizer()
	{
		$this->imageResizer = \core\ObjectPool::getInstance()->getObject('\core\images\resize\ImageResizer');
		$this->imageResizer->setSettings(\core\Configurator::getInstance()->images->resize->getArray());
	}

	public function getImagePath($image, $resolution = null, $watermark = null, $focus = false)
	{
		$this->setData($watermark, $focus)
			->setDataFromImage($image)
			->setResolution($resolution)
			->setImagePath();

		if ($this->isImageCacheNotExists()) {
			$this->checkAndCreateCacheDir();
			$this->imageResizer->resetSettings();
			$this->imageResizer->resizeToFile($this->_origFileName, $this->_imageFullPath,
											$this->_width, $this->_height,
											$this->_watermark, $this->_focus);
		}
		return $this->_imagePath;
	}

	private function setData($watermark, $focus)
	{
		$this->_watermark = $watermark;
		$this->_focus = $focus;
		return $this;
	}

	private function setDataFromImage($image)
	{
		$this->_image = $image;
		$this->_alias = $this->_image->alias;
		$this->_extension = $this->_image->extension;
		$this->_origFileName = $this->_image->getPath();
		$this->_imageUrl = $this->_image->getParentObjectConfig()->getParentConfig()->getConfig()->imagesUrl;
		$this->_imageFileName = $this->_alias.'.'.$this->_extension;
		return $this;
	}

	private function setResolution($resolution)
	{
		$resolution = $resolution ? $resolution : getimagesize($this->_origFileName)[0].'x'.getimagesize($this->_origFileName)[1];
		$resolutions = explode('x', $resolution);
        $this->_width = ($resolutions[0] == '') ? '0' : $resolutions[0];
        $this->_height = ($resolutions[1] == '') ? '0' : $resolutions[1];
		return $this;
	}

	private function setImagePath()
	{
		$this->_imageDirPath = $this->getCacheImageBaseUrl($this->_imageUrl) .
							$this->_width.'x'.$this->_height .
							(empty($this->_focus) ? '' : '_focus') .
							(empty($this->_watermark) ? '' : '_watermark');

		$this->setAdditionalPath();
		return $this;
	}

	private function setAdditionalPath()
	{
		$this->_imagePath = $this->_imageDirPath.'/'.$this->_imageFileName;
		$this->_imageFullPath = DIR.$this->_imagePath;
		return $this;
	}

	protected function getCacheImageBaseUrl($imagesUrl)
	{
		$imageBaseUrl = self::CACHE_DIR . '/' . str_replace('data/','',$imagesUrl);
		return $imageBaseUrl;
	}

	private function isImageCacheNotExists()
	{
		return !file_exists($this->_imageFullPath);
	}

	private function checkAndCreateCacheDir()
	{
		if (!(file_exists(DIR . $this->_imageDirPath) && is_dir(DIR . $this->_imageDirPath))) {
			$this->makeDirs($this->_imageDirPath);
		}
		return $this;
	}

	private function makeDirs($dir)
	{
		\core\utils\Directories::makeDirsRecusive($dir);
		return $this;
	}

	public function getUserImagePath(\core\modules\images\ImageObject $image, $resolution = null, $watermark = null)
	{
		$this->setDataFromUserImage($image, $watermark)
			 ->setResolution($resolution)
			 ->setUserImagePath();

		if ($this->isImageCacheNotExists()) {
			$this->checkAndCreateCacheDir();
			$this->imageResizer->setBgColor($this->_image->rgbBgColor)->setSharpen((boolean)$this->_image->sharpen);
			$this->imageResizer->resizeToFile($this->_origFileName, $this->_imageFullPath,
											$this->_width, $this->_height,
											$this->_watermark, $this->_focus);
		}

		return $this->_imagePath;
	}

	private function setDataFromUserImage($image, $watermark)
	{
		$this->setDataFromImage($image);
		$this->setData($watermark, $this->_image->focus);
		return $this;
	}

	private function setUserImagePath()
	{
		$this->_imageDirPath = $this->getCacheImageBaseUrl($this->_imageUrl) .
							$this->_width.'x'.$this->_height .
							'_user';

		$this->setAdditionalPath();
		return $this;
	}

	public function clearAll()
	{
		$this->_deleteDir(DIR . self::CACHE_DIR);
		return $this;
	}

	protected function _deleteDir($dir)
	{
		$handle = opendir($dir);
		if (!$handle) return;
		while (false !== ($fname = readdir($handle))) {
			if (is_dir( $dir . '/' . $fname)) {
				if (($fname != '.') && ($fname != '..')) {
					$this->_deleteDir( $dir .'/' . $fname);
				}
			} else {
				unlink( $dir .'/' . $fname);
			}
		}
		closedir($handle);
		if ($dir != DIR . self::CACHE_DIR) {
			rmdir($dir);
		}
	}

//==
// удаление закешированных изображений для заданного объекта ImageObject
// НЕ ТЕСТИРОВАНО !
	private function getImagesPath($image)
	{
		$imagePath = $this->getImagePath($image);
		$imagePath = ltrim($imagePath, '/');
		$imagePath = explode('/', $imagePath);
		unset($imagePath[sizeof($imagePath)-1]);
		unset($imagePath[sizeof($imagePath)-1]);

		return implode('/', $imagePath);
	}

	public function clearImageFiles(\core\modules\images\Image $image)
	{
		$this->setDataFromImage($image);
		$imagePath = rtrim($this->getCacheImageBaseUrl($this->_imageUrl),'/');
		$imageFileName = $image->alias . '.' . $image->extension;
		$this->_clearImageFiles(DIR . $imagePath, $imageFileName);
	}

	protected function _clearImageFiles($dir, $imageFileName)
	{
		$handle = opendir($dir);
		if (!$handle) return;
		while (false !== ($fname = readdir($handle))) {
			if (is_dir( $dir . '/' . $fname)) {
				if (($fname != '.') && ($fname != '..')) {
					$this->_clearImageFiles( $dir .'/' . $fname, $imageFileName);
				}
			} else {
				if($fname == $imageFileName) {
					unlink( $dir .'/' . $fname);
				}
			}
		}
		closedir($handle);
	}
}
