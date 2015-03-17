<?php
namespace core\traits;
trait Errors
{
	private $currentLang;
	protected $errors = array();
	protected $errorsList;

	public function setError($key, $text=null)
	{	
		$text = $text ? $text : $this->getErrorsList()['defaultError'][\core\i18n\LangHandler::getInstance()->getLang()];
		$text = (empty($this->getErrorsList()[$key][\core\i18n\LangHandler::getInstance()->getLang()])) ? $text : $this->getErrorsList()[$key][\core\i18n\LangHandler::getInstance()->getLang()];
		$this->addError($key, $text);		
		return $this;
	}

	protected function getLang()
	{
		if(isset($this->currentLang))
			return $this->currentLang;

		$this->setCurrentLang();
		return $this->currentLang;
	}

	private function setCurrentLang()
	{
		$this->currentLang = \core\i18n\LangHandler::getInstance()->getLang();
	}

	public function getErrorsList()
	{
		if (empty($this->errorsList)) {
			include(DIR.'includes/errorsList.php');
			$this->errorsList = $errorsList;
		}
		return $this->errorsList;
	}

	public function addError($key, $value)
	{
		$this->errors[(string)$key] = (string)$value;
		return $this;
	}

	public function getErrors()
	{
		return (empty($this->errors)) ? false : $this->errors;
	}

	public function errorsExists()
	{
		return !empty($this->errors);
	}

	public function errorExists($key)
	{
		return !!$this->getError($key);
	}

	public function getError($key)
	{
		return isset($this->errors[$key][$this->getLang()]) ? $this->errors[$key][$this->getLang()] : false;
	}

	public function resetErrors()
	{
		$this->errors = array();
		return $this;
	}
}