<?php
namespace core\modules\filesUploaded;
class FilesDecorator extends \core\modules\base\ModuleDecorator
{
	const PRIMARY_CATEGORY_ID = 2;
	public $files;

	function __construct($object)
	{
		parent::__construct($object);
	}

	protected function getFilesCategories()
	{
		$files = new FilesUploaded($this->getParentObject());
		return $files->getCategories();
	}

	protected function getFilesStatuses()
	{
		$files = new FilesUploaded($this->getParentObject());
		return $files->getStatuses();
	}

	protected function getFilesByCategory($categories)
	{
	    $files = new FilesUploaded($this->getParentObject());
		$categories = (is_array($categories)) ? implode(',',$categories) : (int)$categories;
	    $files->setSubquery(' AND `objectId` = ?d AND `categoryId` IN (?s)',$this->getParentObject()->id,$categories);
	    return $files;
	}

	protected function getFiles()
	{
	    return new FilesUploaded($this->getParentObject());
	}

}
