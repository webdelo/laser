<?php
namespace core\traits\adapters;
trait I18n
{
	public function _adaptXMLLangGenerator($key)
	{
//		$defaultLang = \core\RequestHandler::getInstance()->getCurrentLang();
//		$xmlTextLangParser = new \core\i18n\TextLangParser('', $defaultLang);
//		
//		foreach ($xmlTextLangParser->getLangs() as $lang){
//			$xmlTextLangParser->set($lang, $this->data[$key.'_'.$lang]);
//		}
//
//		$this->data[$key] = \core\utils\DataAdapt::textValid($xmlTextLangParser->getXML());
	}
}