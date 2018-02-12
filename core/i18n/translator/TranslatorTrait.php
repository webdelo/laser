<?php
namespace core\i18n\translator;
trait TranslatorTrait
{
	use TranslateDataLocatorTrait;
	
	private function getTranslator()
	{
		if ( !$this->_translator ) {
			$this->_translator = new \core\i18n\translator\Translator($this->getLangGettersObject());
		}
		return $this->_translator;
	}
}

