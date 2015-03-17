<?php
namespace core\images\resize;
class ImageResizer {

	private $defaultSettings;
	
    protected $_fileName  = null;
    protected $_image     = null;
    protected $_imageInfo = null;
	
	private $_focusMode = false;

    protected $_settings = array(
        'bgColor' => array(255,255,255),
		'sharpen' => false,
        'transparency' => 127,
        'isConstrant' => true,
        'copyWhenNoNeedResize' => true,
        'jpegQuality' => 100
    );


    public function setSettings($settings = null) {
        if(is_null($settings))
           return $this;
        if (!is_array($settings)) {
            throw new \Exception('Settings for ImageResizer must be array type');
        }
        foreach($settings as $k => $v) {
            if(!isset($this->_settings[$k])) {
                throw new \Exception('Unknown setting key for ImageResizer: ' . $k);
            }
            $chkMethod = '_chk' . ucfirst($k);
            if(!method_exists($this, $chkMethod)) {
                throw new \Exception('Not find method for check settings param in ImageResizer: ' . $chkMethod);
            }
            $this->$chkMethod($v);
            $this->_settings[$k] = $v;
			$this->defaultSettings = $settings;
        }
        return $this;
    }

	public function resetSettings()
	{
		$this->_settings = $this->defaultSettings;
		return $this;
	}
	
	public function setBgColor($bgColor)
	{
		$bgColor = str_replace(' ', '', $bgColor);
		if ( empty($bgColor) ) {
			return $this;
		}
		if (is_string($bgColor))
			$bgColor = $this->convertBgColorStringToArray($bgColor);
		$this->_chkBgColor($bgColor);
		
		$this->_settings['bgColor'] = $bgColor;
		return $this;
	}

	private function convertBgColorStringToArray($bgColor)
	{
		return explode(',', $bgColor);
	}

	public function setSharpen($bool)
	{
		$this->_settings['sharpen'] = (boolean)$bool;
		return $this;
	}

    public function resizeToFile($inFileName, $outFileName, $newWidth = 0, $newHeight = 0, $watermark = null, $focus = false)
    {
		$this->_focusMode = $focus;

		$this->_chkSizes($newWidth, $newHeight);
		$this->_chkFileAccess($inFileName);
		$this->_getImageInfo($inFileName);

		list ($currentImageWidth, $currentImageHeight) = $this->_imageInfo;

		$this->_load($inFileName);

		if($watermark){
			$wm = new \core\images\resize\Watermark();
			$this->_image = $wm->create($this->_image, $watermark);
		}

		if(!$this->_isNoNeedToResize($currentImageWidth, $currentImageHeight, $newWidth, $newHeight))
			$this->_resize($newWidth, $newHeight);

		if (is_null($outFileName))
			$this->_outToBrowser();
		else
			$this->_outToFile($outFileName);

		$this->_reset();
		return $this;
    }

    public function resizeToBrowser($inFileName, $newWidth = 0, $newHeight = 0, $bgColor)
    {
		$bgColor ? $this->_settings['bgColor'] = $this->toRGB($bgColor) : '';
    	return $this->resizeToFile($inFileName, null, $newWidth, $newHeight);
    }

	private function toRGB($hex) {
		$hex = str_replace("color:", "", $hex);
		if(strlen($hex) == 3) {
		   $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		   $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		   $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
		   $r = hexdec(substr($hex,0,2));
		   $g = hexdec(substr($hex,2,2));
		   $b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb; // returns an array with the rgb values
	}

    protected function _chkFileAccess($fileName)
    {
        if(!file_exists($fileName) or !is_readable($fileName)) {
                throw new \Exception('Cannot read image: ' . $fileName);
        }
    }

    protected function _getImageInfo($fileName)
    {
        if(FALSE === ($imageInfo = getimagesize($fileName))) {
                throw new \Exception('GetImageSize error: ' . $fileName);
        }
        $this->_imageInfo = $imageInfo;
    }

    protected function _isNoNeedToResize($currentImageWidth, $currentImageHeight,
					 $newImageWidth, $newImageHeight)
    {
        $noNeed =  (!$newImageWidth and !$newImageHeight) or
		   ($newImageWidth == $currentImageWidth and $newImageHeight == $currentImageHeight) or
                   (!$newImageWidth and $newImageHeight == $currentImageHeight) or
	           (!$newImageHeight and $currentImageWidth == $newImageWidth);
	return $noNeed;
    }

    protected function _setAnySize($w, $h)
    {
         return ($w or $h);
    }

    protected function _getType()
    {
        $mime = $this->_imageInfo['mime'];
        $type = substr(strrchr($mime, '/'), 1);
        return $type;
    }

    protected function _getImageCreateFunction()
    {
    	$type = $this->_getType();
        $imageCreateFunction = 'ImageCreateFrom' . $type;
        if(!function_exists($imageCreateFunction)) {
        	throw new \Exception('Cannot find image create function: ' . $imageCreateFunction);
        }
        return $imageCreateFunction;
    }

    protected function _getImageOutputFunction()
    {
    	$type = $this->_getType();
        $imageOutputFunction = 'Image' . $type;
        if(!function_exists($imageOutputFunction)) {
            throw new \Exception('Cannot find image output function: ' . $imageOutputFunction);
        }
        return $imageOutputFunction;
    }

    protected function _load($fileName)
    {
		$imageCreateFunction = $this->_getImageCreateFunction();
		if(FALSE === ($this->_image = $imageCreateFunction($fileName)))
			throw new \Exception('Image Create Function error: ' . $imageCreateFunction . ' for ' . $fileName);
		$this->_fileName = $fileName;
		return $this;
    }

    protected function _outputImage($outFileName)
    {
    	$imageOutputFunction = $this->_getImageOutputFunction();
        $type = $this->_getType();
    	if (in_array(strtoupper($type),array('JPG','JPEG'))) {
        	$res = $imageOutputFunction($this->_image, $outFileName,
                       $this->_settings['jpegQuality']);
    	} else {
    	    if (is_null($outFileName)) {
            	$res = $imageOutputFunction($this->_image);
            } else {
            	$res = $imageOutputFunction($this->_image, $outFileName);
            }
        }
    	if(FALSE === $res) {
        	throw new \Exception('Image output function error: ' . $this->_fileName);
    	}
    }

    protected function _outToBrowser()
    {
        header('Content-type: ' . $this->_imageInfo['mime']);
        $this->_outputImage(null);
    }

    protected function _fileToBrowser($fileName)
    {
        header('Content-length: ' . filesize($fileName));
		header('Content-type: ' . $this->_imageInfo['mime']);
        readfile($fileName);
    }

    protected function _outToFile($fname)
    {
	$this->_outputImage($fname);
    }

    protected function _reset()
    {
        if(!is_null($this->_image)) {
            imagedestroy($this->_image);
            $this->_image = null;
            unset($this->_filename);
            $this->_fileName = null;
            unset($this->_imageInfo);
            $this->_imageInfo = null;
        }
    }

    protected function _calcResizeParams($w, $h, $newWidth = 0 , $newHeight = 0)
    {
        // default values
        $result = array(
			'left' => 0,
			'top'  => 0,
			'srcX' => 0,
			'srcY' => 0,
			'newWidth'  => $newWidth,
			'newHeight' => $newHeight,
            'newVisibleWidth'  => $newWidth,
			'newVisibleHeight' => $newHeight,
			'w' => $w,
			'h' => $h,
		);

        if (!$newHeight) {
            $ratio = (float)$newWidth / $w;
            $result['newHeight'] = $h * $ratio;
            $result['newVisibleHeight'] = $result['newHeight'];
        } elseif (!$newWidth) {
            $ratio = (float)$newHeight / $h;
            $result['newWidth'] = $w * $ratio;
            $result['newVisibleWidth'] = $result['newWidth'];
        } else {
            if($this->_settings['isConstrant']) {
				$result['newRatio']     = $newRatio     = (float)$newWidth / $newHeight;
                $result['currentRatio'] = $currentRatio = (float)$w / $h;

				$result = ($this->_focusMode && $currentRatio > 0.75)
					? $this->getFocusImageParams($result)
					: $this->getProportionalImageParams($result);
            }
        }
        return $result;
    }

	private function getFocusImageParams($imageParams)
	{
		if ($imageParams['newRatio'] >= $imageParams['currentRatio']) {
			$imageParams['srcX'] = 0;
			$imageParams['srcY'] = ceil( ($imageParams['h'] - ceil( $imageParams['w'] / $imageParams['newRatio'])) / 2);
			$imageParams['h'] -= $imageParams['srcY']*2;
		} else {
			$imageParams['srcY'] = 0;
			$imageParams['srcX'] = ceil( ($imageParams['w'] - ceil($imageParams['h'] * $imageParams['newRatio'])) / 2);
			$imageParams['w'] -= $imageParams['srcX']*2;
		}
		return $imageParams;
	}

	private function getProportionalImageParams($imageParams)
	{
		if ($imageParams['currentRatio'] < $imageParams['newRatio']) { // set new width
			$imageParams['newVisibleWidth'] = $imageParams['w'] * ((float)$imageParams['newHeight'] / $imageParams['h']);
			$imageParams['left'] = (int)(((float)$imageParams['newWidth'] - $imageParams['newVisibleWidth']) / 2);
		} else { // set new height
			$imageParams['newVisibleHeight'] = $imageParams['h'] * ((float)$imageParams['newWidth'] / $imageParams['w']);
			$imageParams['top'] = (int)(((float)$imageParams['newHeight'] - $imageParams['newVisibleHeight']) / 2);
		}
		return $imageParams;
	}

    protected function _setBgAndTransparency($image, $params)
    {
        $transparentIndex = imagecolortransparent($this->_image);
        if( $transparentIndex != -1) {
           $transparentColor = imagecolorsforindex($image, $transparentIndex);
           if(FALSE === $transparentColor) {
               throw new \Exception('Image colors for index function error: ' . $this->_fileName);
           }
           $transparentNew = imagecolorallocate( $image, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue'] );
           $transparentNewIndex = imagecolortransparent( $image, $transparentNew );
           ImageFill( $image, 0,0, $transparentNewIndex );
        } else {
            list($r, $g, $b)  = $this->_settings['bgColor'];
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, $r, $g, $b, $this->_settings['transparency']);
            imageFill($image, 0, 0, $transparent);
        }

    }

    protected function _createTrueColorImage(&$params)
    {
        if (FALSE === ($image = imagecreatetruecolor($params['newWidth'], $params['newHeight']))) {
			throw new \Exception('Image create true color error: ' . $this->_fileName);
        }
        return $image;
    }

    protected function _copyImageResampled($image, $w, $h, &$params)
	{
        if (FALSE === (imagecopyresampled($image, $this->_image,
                            $params['left'], $params['top'],
							$params['srcX'], $params['srcY'],
                            $params['newVisibleWidth'], $params['newVisibleHeight'],
                            $params['w'], $params['h']
                            ))) {
        	throw new \Exception('Image copy resampled error: ' . $this->_fileName);
        }

		if ($this->_settings['sharpen']) {
			$matrix = array(array(-1,-1,-1), array(-1,16,-1), array(-1,-1,-1));
			$divisor = array_sum(array_map('array_sum', $matrix));
			imageconvolution($image, $matrix, $divisor, 0);
			
		}

        $transIndex = imagecolortransparent($this->_image);
        if($transIndex != -1) {
     		imagecolortransparent($image, $transIndex);
             for($y=0; $y<$params['newHeight']; ++$y) {
                for($x=0; $x < $params['newWidth']; ++$x) {
                  if(((imagecolorat($image, $x, $y)>>24) & 0x7F) >= 100) {
                        imagesetpixel($image, $x, $y, $transIndex);
                  }
                }
            }
        	imagetruecolortopalette($image, true, 255);
        	imagesavealpha($image, false);
    	}
    }

    protected function _resize($newWidth = 0, $newHeight = 0)
    {
        list($w, $h) = $this->_imageInfo;
        $params = $this->_calcResizeParams($w, $h, $newWidth, $newHeight);
        $image = $this->_createTrueColorImage($params);
        $this->_setBgAndTransparency($image, $params);
        $this->_copyImageResampled($image, $w, $h, $params);
        imagedestroy($this->_image);
        $this->_image = $image;
    }


    protected function _chkBgColor($color)
    {
        if (!is_array($color) && count($color) !== 3) {
             throw new \Exception('Color must be an array and it\'s length must be equal 3');
        }
        foreach($color as $c) {
            if (!is_int($c) && !($c >= 0 && $c <= 255 ) ) {
                throw new \Exception('Color elements must be an integer in range 0..255');
            }
        }
    }

    protected function _chkTransparency($transparency)
    {
        if (!is_int($transparency) or $transparency < 0 or $transparency > 127) {
             throw new \Exception('Transparency must be an integer in range 0..127');
        }
    }

    protected function _chkIsConstrant($isConstrant)
    {
        if (!is_bool($isConstrant)) {
             throw new \Exception('isConstrant must be bool value');
        }
    }

    protected function _chkCopyWhenNoNeedResize($copyWhenNoNeedResize)
    {
        if (!is_bool($copyWhenNoNeedResize)) {
             throw new \Exception('copyWhenNoNeedResize must be bool value');
        }
    }

    protected function _chkJpegQuality($jpegQuality)
    {
        if (!is_int($jpegQuality) or $jpegQuality < 0 or $jpegQuality > 100) {
             throw new \Exception('JpegQuality must be an integer in range 0..100');
        }
    }

    protected function _chkSizes($w, $h)
    {
		$w = (int)$w;
		$h = (int)$h;
        if (!is_int($w) or $w < 0 or !is_int($h) or $h < 0) {
            throw new \Exception('Width and height must be not negative integer values: ' . $w . ', ' . $h);
        }
    }

	protected function _chkSharpen($sharpen)
	{
		return;
	}	
}