<?php
namespace core\i18n\translator;
trait SerializeTranslatorTrait
{
	use TranslateDataLocatorTrait;

	private function getTranslator()
	{
		if ( !$this->_translator ) {
			$this->_translator = new \core\i18n\translator\SerializeTranslator($this->getLangGettersObject());
		}
		return $this->_translator;
	}
}

