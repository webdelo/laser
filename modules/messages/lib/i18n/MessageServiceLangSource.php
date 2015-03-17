<?php
namespace modules\messages\lib\i18n;
class MessageServiceLangSource extends \core\i18n\translator\FileTranslateData
{
	public function __construct()
	{
		$this->setDataFromFile(DIR.'/'.str_replace("\\", "/", str_replace('Source', '', get_class($this))).'Array.php');
	}
}