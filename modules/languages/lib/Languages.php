<?php
namespace modules\languages\lib;
class Languages extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\languages\lib\LanguageConfig';
	protected $objectClassName = '\modules\languages\lib\Language';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function getLangByIdsString($ids)
	{
		return $this->resetFilters()
					->setSubquery(' AND `id` IN(?s) ', $ids);
	}

	public function getLang()
	{
		return $this->resetFilters()
					->setSubquery(' AND `use` = ?d ', 1)
					->setSubquery('ORDER BY `name` ASC');
	}

	public function getSystemLangsArray()
	{
		$langs = array();
		foreach(\core\i18n\LangHandler::getInstance()->getLangs() as $alias)
			$langs[] = $this->getObjectByAlias($alias);
		return $langs;
	}
}