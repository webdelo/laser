<?php
namespace core\i18n;
class TextLangParser
{
	protected $XMLobject;
	protected $defaultLang = 'ru';

	public function __construct($text, $defaultLang = 'ru')
	{
		$this->setDefaultLang($defaultLang);
		$text = htmlspecialchars_decode($text);
		
		if ($this->isValidXML($text)) {
			$this->XMLobject = new \SimpleXMLElement($text);
		} else {
			$xmltext = $this->transformToXML($text, $defaultLang);
			$this->XMLobject = new \SimpleXMLElement($xmltext);
		}
	}
	
	private function setDefaultLang($defaultLang) 
	{
		$this->defaultLang = $defaultLang;
		return $this;
	}
	
	private function getDefaultLang() 
	{
		return $this->defaultLang;
	}

	public function get($lang, $default = true)
	{
		$defaultLng = $this->getDefaultLang();
		
		if (!isset($lang) || (empty($lang))) $lang = $this->getDefaultLang();
		if (isset($this->XMLobject->$lang)) {
			return $this->XMLobject->$lang;
		} else if ($default == true) {
			return $this->XMLobject->$defaultLng;
		} else {
			if ($lang == $this->getDefaultLang()) return $this->XMLobject->$defaultLng;
			return NULL;
		}
	}
	
	public function set($lang, $text)
	{
		
		$this->XMLobject->$lang = '<![CDATA[' . $text . ']]>';
		
		return html_entity_decode($this->XMLobject->asXML(), ENT_NOQUOTES, 'UTF-8');
	}
	
	public function getXML()
	{
		return html_entity_decode($this->XMLobject->asXML(), ENT_NOQUOTES, 'UTF-8');
	}
	
	public function getLangs()
	{
		$langs = array();
		foreach ($this->XMLobject as $node) {
			$langs[] = $node->getName();
		}
		return $langs;
	}

	private function isValidXML($string)
	{
		return @simplexml_load_string($string); //do not remove the error supressor
	}
	
	private function transformToXML($string,$lang)
	{
		$result = '<xml>' . '<' . $lang . '><![CDATA[' . $string . ']]></' . $lang . '>' . '</xml>';
		return $result;
	}
}
