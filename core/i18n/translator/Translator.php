<?php
namespace core\i18n\translator;
class Translator implements ITranslator
{
	private $object;
	private $lang;
	private $replacement;

	public function __construct(ITranslateData $object)
	{
		$this->setObject($object)
			 ->setCurrentLang();
	}

	private function setObject(ITranslateData $object)
	{
		$this->object = $object;
		return $this;
	}

	protected function getObject()
	{
		return $this->object;
	}

	public function setCurrentLang()
	{
		return $this->setLang(\core\i18n\LangHandler::getInstance()->getLang());
	}

	public function setLang($lang)
	{
		$this->lang = $lang;
		return $this;
	}

	protected function getLang()
	{
		return $this->lang;
	}

	public function get($alias, $data = null)
	{
		$args = func_get_args();
		array_shift($args);
		$this->replacement = $this->mergeArgsInOneArray($args);
		return $this->getStringByAlias($alias);
	}

	private function mergeArgsInOneArray(array $args)
	{
		$res = array();
		foreach ($args as $element)
			if (is_array($element))
				$res = array_merge ($res, $element);
			else
				$res[] = $element;
		return $res;
	}

	private function getStringByAlias($alias)
	{
		return $this->strReplace( $this->getObject()->getPattern($alias, $this->getLang()) );
	}

	private function strReplace($subject)
	{
		$subject = preg_replace_callback( "/(\?s)/", array($this,'convertPlaceholders'), $subject);
		return $subject;
	}

	private function convertPlaceholders($type)
	{
		$value = array_shift($this->replacement);
		return $value;
	}
}