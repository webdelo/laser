<?php
namespace core\modules\filesUploaded;
trait FilesTraitDecorator
{
    private $files;

    public function getFilesCategories()
    {
        return $this->getFiles()->getCategories();
    }

    public function getFilesStatuses()
    {
        return $this->getFiles()->getStatuses();
    }

    public function getFilesByCategory($categories)
    {
        $files = new FilesUploaded($this);
        $categories = is_array($categories) ? implode(',',$categories) : (int)$categories;
        $files->setSubquery(' AND `objectId` = ?d AND `categoryId` IN (?s) AND `statusId` = ?d ',$this->id,$categories, FileUploadedConfig::STATUS_ACTIVE)
            ->setOrderBy(' `id` ASC ');
        return $files;
    }

    public function getFiles()
    {
        if (empty($this->files))
            $this->files = new FilesUploaded($this);
        return $this->files;
    }
}