<?php
namespace core\i18n;
class TextLangCompacter
{
	private $object;
	private $post;
	private $permissibleObjectClasses = array(
		'\core\modules\base\ModuleObject',
		'\core\modules\base\ModuleObjects'
	);

	public function __construct($object, $post)
	{
		$this->checkClass($object)
			->setObject($object)
			 ->setPost($post);
	}

	private function checkClass($object)
	{
		foreach ($this->permissibleObjectClasses as $class)
			if($object instanceof $class)
				return $this;
		throw new \Exception('Object passed in '.get_class($this).' is not valid instance!');
	}

	private function setObject($object)
	{
		$this->object = $object;
		return $this;
	}

	private function getObject()
	{
		return $this->object;
	}

	private function setPost($post)
	{
		$this->post = $post;
		return $this;
	}

	private function _getPost()
	{
		return $this->post;
	}

	public function getPost()
	{
		$fields = $this->getObject()->getConfig()->getObjectFields();
		foreach($fields as $field) {
			$this->compact($field);
		}
		return $this->post;
	}

	private function compact($field)
	{
		$defaultLang = \core\RequestHandler::getInstance()->getCurrentLang();
		$xmlTextLangParser = new \core\i18n\TextLangParser('', $defaultLang);
		$flag = false;
		foreach ($this->getLangs() as $lang){
			if ( isset($this->_getPost()[$field.'_'.$lang]) ) {
				$xmlTextLangParser->set($lang, $this->_getPost()[$field.'_'.$lang]);
				$flag = true;
			}
		}
		if ($flag)
			$this->post[$field] = $xmlTextLangParser->getXML();
	}

	private function getLangs()
	{
		return LangHandler::getInstance()->getLangs();
	}
}