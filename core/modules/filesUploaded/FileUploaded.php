<?php
namespace core\modules\filesUploaded;
class FileUploaded extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new FileUploadedObject($objectId, $configObject);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\categories\CategoryDecorator($object);
		parent::__construct($object);
	}

	protected function download()
	{
		$downloadStart = new \core\files\Downloader($this);
	}

	protected function getFileIcon()
	{
		$fileIcon = new FileIcon();
		return $fileIcon->getFileIconByType(mime_content_type(DIR.$this->getRealPath()));
	}
}