<?php
namespace core\i18n\traits;
trait ObjectLangTrait
{
	private $_lang;
	private $_observers = array();

	protected function getLang($lang = null)
	{
		if (empty($lang)){
			if (empty($this->_lang))
				$this->_lang = \core\RequestHandler::getInstance()->getCurrentLang();
			return $this->_lang;
		}
		return $lang;
	}

	public function setLang($lang)
	{
		$this->_lang = (string)$lang;
		foreach ($this->_observers as $object)
			$object->setLang($this->_lang);
		return $this;
	}

	protected function addLangObserver(\core\i18n\interfaces\Ii18n $object)
	{
		$object->setLang($this->getLang());
		$this->_observers[] = $object;
	}
}