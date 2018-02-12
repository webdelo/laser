<?php
namespace controllers\base;
class ImagesBaseController extends Controller
{
	private $alias = '';
	private $resolution = array( 'width' => 0, 'height' => 0 );
	private $owner = '';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __call($name, $arguments)
	{
		$this->defaultAction($name, $arguments[0]);
	}
	
	private function defaultAction($owner, $imageData)
	{
		$this->setOwner($owner)
			 ->setAlias($imageData)
			 ->setResolution($imageData)
			 ->createImage();
	}
	
	private function setOwner($owner)
	{
		$owner = ucfirst($owner);
		$this->owner = new $owner();
		
		return $this;
	}
	
	private function setAlias($data)
	{
		$image = array_pop($data);
		$image = explode('.', $image);
		$this->alias = $image[0];
		
		return $this;
	}
	
	private function setResolution($data)
	{
		if (sizeof($data)>1) {
			$resolution = array_shift($data);
			$resolution = explode('x', $resolution);

			$this->resolution = array(
				'width'  => $resolution[0],
				'height' => $resolution[1]
			);
		}
		
		return $this;
	}
	
	private function createImage()
	{
		$id = $this->owner->images->getIdByAlias($this->alias);
		$image = $this->owner->images[$id];
		$resizer = new ImageResizer();
		$resizer->resizeToBrowser($image->getPath(), $this->resolution['width'], $this->resolution['height']);
	}
}