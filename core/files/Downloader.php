<?php
namespace core\files;
class Downloader extends \core\modules\base\ModuleObject
{
	function __construct($object)
	{
		$this->download($object);
	}

	protected function download($file)
	{
		$fileName = $file->alias.'.'.$file->extension;
		$realFilePath = DIR.$file->getRealPath();

		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack");
		header("Content-Type: application/octet-stream");
		header("Content-Length: " .(string)(filesize($realFilePath)) );
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Content-Transfer-Encoding: binary\n");

		readfile($realFilePath);
	}
}