<?php
namespace core\modules\categories;
class TranslateCategories extends \core\modules\base\ModuleObjects implements \core\i18n\interfaces\Ii18n
{
	use \core\modules\statuses\StatusesTraitDecorator,
		\core\i18n\traits\ObjectLangTrait;
	protected $configClass = '\core\modules\categories\TranslateCategoryConfig';

	// reimplemented method with lang-setting
	protected function &getModuleObject($id)
	{
		$translateCategory = parent::getModuleObject($id);
		$this->addLangObserver($translateCategory);
		return $translateCategory;
	}

	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}

}