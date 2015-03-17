<?php
namespace modules\clients\components\clientSMS\lib\i18n;
class ClientSMSLangSource extends \core\i18n\translator\FileTranslateData
{
	public function __construct()
	{
		$this->setDataFromFile(DIR.'/'.str_replace("\\", "/", str_replace('Source', '', get_class($this))).'Array.php');
	}
}