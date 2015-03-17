<?php
namespace core\modules\statuses;
class TranslateStatus extends \core\modules\base\ModuleObject
{
	use \core\traits\ObjectPool,
		\core\i18n\TextLangParserTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\traits\ObjectLangTrait;
	
	protected $configClass = '\core\modules\statuses\TranslateStatusConfig';

	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}

	/* Start: Main Data Methods */
	public function getName($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['name'], $this->getLang($lang));
	}
}