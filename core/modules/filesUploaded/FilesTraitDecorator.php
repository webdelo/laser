<?php
namespace core\modules\filesUploaded;
trait FilesTraitDecorator
{
	private $files;

	protected function getFilesCategories()
	{
		$files = new FilesUploaded($this);
		return $files->getCategories();
	}

	protected function getFilesStatuses()
	{
		$files = new FilesUploaded($this);
		return $files->getStatuses();
	}

	protected function getFilesByCategory($categories)
	{
	    $files = new FilesUploaded($this);
		$categories = (is_array($categories)) ? implode(',',$categories) : (int)$categories;
	    $files->setSubquery(' AND `objectId` = ?d AND `categoryId` IN (?s)', $this->id, $categories);
	    return $files;
	}

	protected function getFiles()
	{
	    return new FilesUploaded($this);
	}

}