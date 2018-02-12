<?php 
namespace core\captcha;
class Captcha
{
	use \core\traits\RequestHandler;
	
	protected $font = "comicbd.ttf"; //font file
	protected $text;
	protected $lines = 8; //number of noize lines
	protected $width = 90;
	protected $height = 25;
	
	
	function __construct($width, $height)
	{
		$this->setSize($width, $height);
		$this->generateText();
		$this->saveText();
	}
	
	protected function setSize($width, $height)
	{
		if($width)
			$this->width = $width;
		if ($height)
			$this->height = $height;
	}
	
	protected function generateText()
	{
		$num1 = rand(111111,999999);
		$num2 = date("D");
		$num3 = date("M");
		$num = $num1;
		$mix = preg_split("//",$num,-1,PREG_SPLIT_NO_EMPTY);
		shuffle($mix);
		$num = implode ($mix);
		$b = strlen ($num);
		$b2 = $b - 6;
		$c = rand (1,$b2);
		$this->text = substr($num,$c,6);
	}
	
	
	protected function saveText()
	{
		session_start();
		$this->getSESSION()['captcha'] = $this->text;
	}
	
	
	public function getImg()
	{
		header ("Content-type: image/png");
		
		$rotate = rand(5,-5); //turn text angels
		
		$img = imagecreate($this->width, $this->height); 
		$back = imagecolorallocate($img, rand(220,255), rand(220,255) ,rand(220,255));//background cilor
		$line_color = imagecolorallocate($img, rand(0,200), rand(100,200), rand(100,200));//lines color
		$color = imagecolorallocate($img, rand(0,150), rand(0,150), rand(0,150));//text color
		//border
		$w = $this->width - 1;
		$h = $this->height - 1;
		imageline($img, 0, 0, $w, 0, $color); 
		imageline($img, 0, 0, 0, $h , $color); 
		imageline($img, 0, $h, $w, $h , $color); 
		imageline($img, $w, 0, $w, $h , $color); 
		
		for ($i=0; $i<$this->lines; $i++) {
			imageline($img,rand(0,90),rand(0,5),rand(0,90),rand(20,25),$line_color);
			}
		//input text
		imagettftext($img,15,$rotate,7,20,$color,DIR.'fonts/'.$this->font,$this->text); 
		imagepng($img);
		imagedestroy($img);
	}
	
	
	
	
}
?>