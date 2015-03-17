<?php
namespace modules\clients\components\clientMailers\lib\i18n;
class ClientMailersLangSource extends \core\i18n\translator\FileTranslateData
{
	public function __construct()
	{
		$this->setDataFromFile(DIR.'/'.str_replace("\\", "/", str_replace('Source', '', get_class($this))).'Array.php');
	}
}