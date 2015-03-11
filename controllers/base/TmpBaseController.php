<?php
namespace controllers\base;
class TmpBaseController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		if (method_exists($this, $name))
				$this->$name();
		else
			$this->redirect404();
	}

	private function images()
	{
		$data = \core\url\UrlDecoder::getInstance()->getArguments();

		if(empty($data))
			return $this->redirect404 ();

		$resolution = array('width' => 0, 'height' => 0);

		if (sizeof($data)>1) {
			$resData = array_shift($data);
			$resData = explode('x', $resData);

			$resolution = array(
				'width'  => $resData[0],
				'height' => $resData[1]
			);

			$bgColor = isset($resData[2]) ? $resData[2] : 'null';
		}
		$imagePath = DIR.'/tmp/'.__FUNCTION__.'/'.$data[0];
		$resizer = new \core\images\resize\ImageResizer();
		$resizer->resizeToBrowser($imagePath , $resolution['width'], $resolution['height'], $bgColor);
	}
}