<?php
namespace core\modules\filesUploaded;
class FileUploaded extends \core\modules\base\ModuleDecorator
{
    use	\core\traits\ObjectPool,
        \core\modules\statuses\StatusTraitDecorator,
        \core\modules\categories\CategoryTraitDecorator;

    function __construct($objectId, $configObject)
    {
        $object = new FileUploadedObject($objectId, $configObject);
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