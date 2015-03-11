<?php
namespace core\modules\filesUploaded;
class FilesUploaded extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new FilesUploadedObject($configObject);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}


}