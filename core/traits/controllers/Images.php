<?php
/*
This methods must be in $permissibleActions!
protected $permissibleActions = array
	(
		// Start: Images Trait Methods
		'uploadImage',
		'addImagesFromEditPage',
		'removeImage',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editImage',
		'getTemplateToEditImage',
		'ajaxGetImagesBlock',
		'getImagesListBlock',
		// End: Images Trait Methods
	);
 */
namespace core\traits\controllers;
trait Images
{
	protected $imagesArrayKey = 'imagesData' ;
	
	protected function uploadImage()
	{
		$imagesFilesUploader = new \core\modules\images\ImagesFileUploader();
		$this->ajaxResponse($imagesFilesUploader->upload());
	}

	protected function addImagesFromEditPage()
	{
		$this->setObject($this->_config->getObjectClass(), $this->getREQUEST()[0])->addImages();
		$this->ajax(true);
	}

	protected function addImages()
	{
		if (isset($this->getPOST()[$this->imagesArrayKey]))
			if ($this->getPOST()[$this->imagesArrayKey])
				foreach ($this->getPOST()[$this->imagesArrayKey] as $image)
					$this->getObject('\core\modules\images\Images',$this->modelObject)->add($image);
		return $this;
	}

	protected function removeImage()
	{
		$this->ajaxResponse($this->getImageObject()->delete());
	}

	protected function setPrimary()
	{
		$this->ajaxResponse($this->getImageObject()->edit(array('categoryId'=>2)));
	}

	protected function resetPrimary()
	{
		$this->ajaxResponse($this->getImageObject()->edit(array('categoryId'=>1)));
	}

	protected function setBlock()
	{
		$this->ajaxResponse($this->getImageObject()->edit(array('statusId'=>2)));
	}

	protected function resetBlock()
	{
		$this->ajaxResponse($this->getImageObject()->edit(array('statusId'=>1)));
	}

	protected function editImage()
	{
		$this->ajaxResponse($this->getImageObject()->edit($this->getPOST()));
	}
	
	protected function getImageObject()
	{
		return (new $this->objectsClass)->getImages()->getObjectById((int)$this->getREQUEST()[0]);
	}

	protected function getTemplateToEditImage()
	{
		$imageId  = (int)$_REQUEST[0];
		$objects  = $this->_config->getNewObjects();
		$image    = $objects->getImages()->getObjectById($imageId);
		$cache = new \core\images\resize\Cache();

		$this->setObject($objects)
			 ->setContent('image', $image)
			 ->setContent('objects', $objects)
			 ->setContent('imageRealSizePath', $cache->getImagePath($image))
			 ->includeTemplate('image');
	}

	protected function ajaxGetImagesBlock()
	{
	    return $this->getImagesBlock($this->getPOST()['objectId']);
	}
	
	protected function getImagesBlock($objectId)
	{
	    $object = new \core\Noop();
	    if (isset($objectId)) {
			$object = $this->getObject($this->_config->getObjectClass(), $objectId);
	    }

	    $objects = new $this->objectsClass;
	    $this->setContent('object', $object) // Need for images template
		     ->setContent('objects', $objects) // Need for images template
		     ->includeTemplate('newImagesForm');
	}
	
	protected function ajaxGetImagesListBlock()
	{
	    $this->getImagesListBlock($this->getPOST()['objectId']);
	}

	protected function getImagesListBlock($objectId)
	{
	    $object = new \core\Noop();
	    if (isset($objectId)) {
		    $object = $this->getObject($this->_config->getObjectClass(), $objectId);
	    }

	    $this->setContent('object', $object) // Need for images template
		     ->includeTemplate('imagesList');
	}
	
	public function setPriority()
	{
		$object = $this->getObject($this->objectClass, $this->getGET()->objectId);
		foreach($this->getGET()['data'] as $id=>$priority){
			$image = $object->getImageById($id);
			$image->edit(array('id'=>$id, 'priority'=>$priority), array('id', 'priority'));
		}
		$this->ajaxResponse(true);
	}
}