<?php
namespace core\images\resize;
class Watermark
{
	private $text = '';
	private $font = 'images/fonts/a_BodoniNova_BoldItalic.ttf';
	private $rgb = array(255, 255, 255);		// 0-255
	private $transparency = 20;		// 0-128

	private $watermarkPng = 'images/fonts/watermark/watermark.png';
	private $watermarkTypes = array('watermark', 'watermarkPng');
	private $image;
	private $r;
	private $g;
	private $b;



	public function create($image, $watermark)
	{
		$this->setImage($image);
		$this->setRgb();
		if(in_array($watermark, $this->getWatermarkTypes()))
			return $this->$watermark($this->image);
	}

	private function setImage($image)
	{
		if($this->isResource($image))
			$this->image = $image;
		return $this;
	}

	private function isResource($image)
	{
		if (gettype($image) != 'resource')
			throw new \Exception ( 'Try to set invalid variable in '.get_class($this).'!' );
		return true;
	}

	private function setRgb()
	{
		$this->r = $this->rgb[0];
		$this->g = $this->rgb[1];
		$this->b = $this->rgb[2];
	}

	public function getWatermarkTypes()
	{
		return $this->watermarkTypes;
	}

	private function watermarkAddText($img)
	{
		$width = imagesx($img);
		$height = imagesy($img);
		$angle = -rad2deg(atan2((-$height),($width)));

		$text = " ".$this->getTExt()." ";

		$c = imagecolorallocatealpha($img, $this->r, $this->g, $this->b, $this->transparency);

		$size = (($width+$height)/2)*2/strlen($text);
		$box  = imagettfbbox ( $size, $angle, $this->getFont(), $text );
		$x = $width/2 - abs($box[4] - $box[0])/2;
		$y = $height/2 + abs($box[5] - $box[1])/2;

		imagettftext($img ,$size, $angle, $x, $y, $c, $this->getFont(), $text);
		return $img;
	}

	protected function getFont()
	{
		return DIR.$this->font;
	}

	protected function getText()
	{
		return empty($this->text) ? \core\url\UrlDecoder::getInstance()->getDomainAlias() : $this->text;
	}

	public function watermark($img)
	{
		$x = imagesx($img);
		$y = imagesy($img);

		$watermark = imagecreatetruecolor($x, $y);
		$mark_bg = imagecolorallocate($watermark, 0, 0, 0);
		$mark_col = imagecolorallocate($watermark, $this->r, $this->g, $this->b);
		imagefill($watermark, 0, 0, $mark_bg);
		imagecolortransparent($watermark, $mark_bg);
		$watermark = $this->watermarkAddText($watermark);

		$mark_bg = imagecolorallocate($watermark, 0, 0, 0);  // 240
		$mark_col = imagecolorallocate($watermark, 10, 10, 10);	// 240
		imagefill($watermark, 0, 0, $mark_bg);
		imagecolortransparent($watermark, $mark_bg);

		return $this->mergeImages($img, $watermark);
	}

	private function mergeImages($img, $watermark)
	{
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$dest_x = imagesx($img) - $watermark_width - 5;
		$dest_y = imagesy($img) - $watermark_height - 5;
		imagecopymerge($img, $watermark, 5, 5, 0, 0, $watermark_width, $watermark_height, $this->transparency);
		imagedestroy($watermark);
		return $img;
	}

	public function watermarkPng($img)
	{
		$watermark = imagecreatefrompng(DIR.$this->watermarkPng);

		$newWatermark = imagecreatetruecolor(imagesx($img), imagesy($img));
		$mark_bg = imagecolorallocate($newWatermark, 0, 0, 0);
		$mark_col = imagecolorallocate($newWatermark, $this->r, $this->g, $this->b);
		imagefill($newWatermark, 0, 0, $mark_bg);
		imagecolortransparent($newWatermark, $mark_bg);
		imagecopyresampled($newWatermark, $watermark, 0, 0, -20, -350, imagesx($img), imagesy($img), imagesx($watermark)+600, imagesy($watermark)+500);

		return $this->mergeImages($img, $newWatermark);
	}
}

