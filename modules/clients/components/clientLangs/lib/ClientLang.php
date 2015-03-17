<?php
namespace modules\clients\components\clientLangs\lib;
class ClientLang extends \core\modules\base\ModuleObject
{
	protected $configClass = 'modules\clients\components\clientLangs\lib\ClientLangConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}