<?php
namespace core\i18n;
class LangHandler
{
	use \core\traits\RequestHandler;
	
	static $object;
	private $lang;
	private $langs;
	
	static public function getInstance ()
	{
		if (is_null(self::$object))
			self::$object = new LangHandler();
		return self::$object;
	}
	
	public function __construct()
	{
		$this->setDataFromGET()
			 ->setDataFromSESSION()
			 ->setDataFromDecoder();
	}
	
	private function setDataFromGET()
	{
		if ( isset($this->getGET()['lang']) && empty($this->lang) ) {
			if ( $this->checkLang($this->getGET()['lang']) ) {
				$this->lang = $this->getGET()['lang'];
				
				$this->setLangInSESSION($this->lang);
			}
		}	
		return $this;
	}
	
	public function setLangInSESSION($lang)
	{
		$_SESSION['lang'] = $lang;
		return true;
	}
	
	private function checkLang($lang)
	{
		return in_array($lang, $this->getLangs());
	}
	
	public function getLangs()
	{
		if (!$this->langs) {
			$this->langs = array_keys(\core\Configurator::getInstance()->url->getArrayByKey('langs'));
		}
		return $this->langs;
	}
	
	private function setDataFromSESSION()
	{
		if ( isset($this->getSESSION()['lang']) && empty($this->lang) ) {
			$this->lang = $this->checkLang($this->getSESSION()['lang']) ? $this->getSESSION()['lang'] : '';
		}
		return $this;
	}
	
	private function setDataFromDecoder()
	{
		if ( \core\url\UrlDecoder::getInstance()->lang && empty($this->lang) ) {
			$this->lang = $this->checkLang(\core\url\UrlDecoder::getInstance()->lang) ? \core\url\UrlDecoder::getInstance()->lang : '';
		}
		return $this;
	}
	
	public function getLang()
	{
		return $this->lang ? $this->lang : $this->getDefaultLang();
	}

	public function setLang($lang)
    {
        $this->lang = $lang;
    }
	
	public function getDefaultLang() 
	{
		return \core\Configurator::getInstance()->url->getArrayByKey('default')['lang'];
	}
}
