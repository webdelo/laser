<?php
namespace core\traits\controllers;
trait Downloads
{
	protected function getFileRequestedForDownload($object)
	{
		$last = \core\utils\Utils::getLastElementFromRequest();
		$str = explode('.', $last);
		if(sizeof($str)>1){
			$extension = end($str);
			$str = implode('.', $str);
			$fileAlias = substr($str, 0, strlen($str)-4);

			$files = new FilesUploaded($object);
			$filters = array(
				'where' => array(
					'query' => '`objectId` = "?d"  AND  `alias` = "?s"  AND  `extension` = "?s" ',
					'data'  => array($object->id, $fileAlias, $extension),
				),
				'limit' => 1
			);
			$files->setFilters($filters);
			$file = $files->current();

			return $file instanceof FileUploaded ? $file : false;
		}
		return false;
	}
}