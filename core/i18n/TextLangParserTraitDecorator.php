<?php
namespace core\i18n;
trait TextLangParserTraitDecorator
{
	protected function getTextFromLangParser($text, $lang = null)
	{
		$this->checkTraitsRequires();
		$lang = empty($lang) ? $this->getCurrentLang() : $lang;
		return (new \core\i18n\TextLangParser($text))->get($lang);
	}

	private function checkTraitsRequires()
	{
		if (in_array('getCurrentLang', get_class_methods($this)))
			return $this;
		throw new \Exception('Requires implementation of the method "getCurrentLang" for trait "\core\i18n\TextLangParserTraitDecorator" in object '.get_class($this).'!');
	}
}
