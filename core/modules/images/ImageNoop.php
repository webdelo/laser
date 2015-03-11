<?php
namespace core\modules\images;
class ImageNoop extends \core\Noop
{
	public function getImage($resize = null)
	{
		if (!$this->validResizeString($resize)) {
			throw new \Exception('Resize arguments are not valid!');
		}

		$resolution = ($resize) ? $resize :'0x0';

		$sizes = $this->getSizesFromResolution($resolution);
		$width = $sizes[0];
		$height = $sizes[1];

		if (!file_exists(DIR.'cache/images/noimage/'.$resolution.'/noimage.png')) { // если файла нет в кеше - создаем его
			if (!(file_exists(DIR.'cache/images/noimage/'.$resolution.'/') && is_dir(DIR.'cache/images/noimage/'.$resolution.'/'))) {
				$this->makeDirs('/cache/images/noimage/'.$resolution);
			}
			$resizer = new \core\images\resize\ImageResizer();
			$resizer->resizeToFile(DIR.NO_IMAGE_PATH.'noimage.png', DIR.'cache/images/noimage/'.$resolution.'/noimage.png', $width, $height);
		}
		return '/cache/images/noimage/'.$resolution.'/noimage.png';
	}

	private function validResizeString($resize = null)
	{
		if ($resize) {
			$values = explode('x', $resize);
			return (sizeof($values) == 2);
		}
		return true;
	}

	protected function getSizesFromResolution($resolution)
	{
		$resolutions = explode('x', $resolution);
		$width = ($resolutions[0] == '') ? '0' : $resolutions[0];
		$height = ($resolutions[1] == '') ? '0' : $resolutions[1];
		return array($width, $height);
	}

	private function makeDirs($dir)
    {
        \core\utils\Directories::makeDirsRecusive($dir);
		return $this;
    }
}
