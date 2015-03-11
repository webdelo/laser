<?php
namespace modules\articles\lib;
class Articles extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ArticlesObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}
}