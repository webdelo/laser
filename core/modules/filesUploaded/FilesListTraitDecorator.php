<?php
namespace core\modules\filesUploaded;
trait FilesListTraitDecorator
{
	private $files;

	private function getFiles($object)
	{
		if (empty($this->files)){
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
		return $this->files;
	}

}
