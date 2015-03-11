<?php
/*
This methods must be in $permissibleActions!
protected $permissibleActions = array
	(
		// Start: Files Trait Methods
		'uploadFile',
		'addFilesFromEditPage',
		'removeFile',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editFile',
		'getTemplateToEditFile',
		'ajaxGetFilesBlock',
		'getFilesListBlock',
		 'getFileIcon',
		// End: Files Trait Methods
	);
 */
namespace core\traits\controllers;
trait Files
{
	protected $filesArrayKey = 'filesData' ;

	protected function uploadFile()
	{
		$filesFilesUploader = new \core\modules\filesUploaded\FilesFileUploader();
		$this->ajaxResponse($filesFilesUploader->upload());
	}

	protected function addFilesFromEditPage()
	{
		$this->setObject($this->_config->getObjectClass(), $this->getREQUEST()[0])->addFiles();
		$this->ajax(true, 'ajax', true);
	}

	protected function addFiles()
	{
		if (isset($_POST[$this->filesArrayKey]))
			if ($_POST[$this->filesArrayKey])
				foreach ($_POST[$this->filesArrayKey] as $file)
					$this->getObject('\core\modules\filesUploaded\FilesUploaded',$this->modelObject)->add($file);
		return $this;

	}

	protected function ajaxGetFilesBlock()
	{
	    return $this->getFilesBlock($this->getPOST()['objectId']);
	}

	protected function getFilesBlock($objectId)
	{
	    $object = new \core\Noop();
	    if (isset($objectId)) {
			$object = $this->getObject($this->_config->getObjectClass(), $objectId);
	    }

	    $objects = new $this->objectsClass;
	    $this->setContent('object', $object) // Need for images template
		     ->setContent('objects', $objects) // Need for images template
		     ->includeTemplate('newFilesForm');
	}

	protected function ajaxGetFilesListBlock()
	{
	    $this->getFilesListBlock($this->getPOST()['objectId']);
	}

	protected function getFilesListBlock($objectId)
	{
	    $object = new \core\Noop();
	    if (isset($objectId)) {
		    $object = $this->getObject($this->_config->getObjectClass(), $objectId);
	    }

	    $this->setContent('object', $object) // Need for images template
		     ->includeTemplate('filesList');
	}

	protected function removeFile()
	{
		$file = new \core\modules\filesUploaded\FileUploaded($this->getREQUEST()[0], $this->_config);
		$this->ajaxResponse($file->delete());
	}

	protected function editFile()
	{
		$file = new \core\modules\filesUploaded\FileUploaded($this->getREQUEST()[0], $this->_config);
		$this->ajaxResponse($file->edit($this->getPOST()));
	}

	protected function getTemplateToEditFile()
	{
		$fileId  = (int)$_REQUEST[0];
		$objects  = $this->_config->getNewObjects();
		$file = $objects->getFiles()->getObjectById($fileId);

		$this->setObject($objects)
			->setContent('file', $file)
			->setContent('objects', $objects)
			->includeTemplate('file');
	}

	protected function getFileIcon()
	{
		$fileIcon = new \core\modules\filesUploaded\FileIcon();
		echo $fileIcon->getFileIconByType(mime_content_type(DIR.$this->getPost()['pathToFile']));
	}

	protected function download()
	{
		if (isset($this->getREQUEST()[1])){
			$objectsClass = $this->_config->getObjectsClass();
			$objects = new $objectsClass;
			$file = $objects->getObjectById($this->getREQUEST()[0])->getFiles()->getObjectByAlias($this->getREQUEST()[1]);
			if($file instanceof \core\modules\filesUploaded\FileUploaded)
				$downloader = new \core\files\Downloader($file);
		}
	}

}