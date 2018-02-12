<?php
namespace core\modules\filesUploaded;
class FilesListDecorator extends \core\modules\base\ModuleDecorator
{
	public $files;

	function __construct($object)
	{
		parent::__construct($object);
		$this->instantFiles($object);
	}

	private function instantFiles($object)
	{
		$filters = array(
			'where' => array(
				'query' => '`objectId` = "?d" ',
				'data'  => array($object->id),
			),
			'order_by' => 'id ASC'
		);
		$this->files = new FilesUploaded($object);
		$this->files->setFilters($filters);
	}

}
