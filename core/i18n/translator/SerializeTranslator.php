<?php
namespace core\i18n\translator;
class SerializeTranslator implements ITranslator
{
	private $_translator;

	public function __construct(ITranslateData $object)
	{
		$this->setTranslator($object);
	}

	private function setTranslator(ITranslateData $object)
	{
		$this->_translator = new Translator($object);
		return $this;
	}

	private function getTranslator()
	{
		return $this->_translator;
	}

	public function setCurrentLang()
	{
		$this->_translator->setCurrentLang();
		return $this;
	}

	public function setLang($lang)
	{
		$this->getTranslator()->setLang($lang);
		return $this;
	}

	public function getLang()
	{
		return $this->getTranslator()->getLang();
	}

	public function get($alias, $data = null)
	{
		return serialize(func_get_args());
	}

	public function getFromSerialize($serialize)
	{
		if (is_null($serialize)) {
			return;
		}
		
		return (!!@unserialize($serialize))
			? call_user_func_array(array($this->getTranslator(), 'get'), unserialize($serialize)) 
			: $serialize;
	}
}