<?php
namespace modules\cabinet\lib;
abstract class CabinetBase
{
	use \core\traits\controllers\Authorization,
		\core\i18n\translator\TranslatorTrait;
	
	public function getName()
	{
		return $this->getTranslator()->get($this->getShortClassName());
	}
	
	public function getPath()
	{
		return '/cabinet/'.$this->getShortClassName().'/';
	}
	
	public function validatePath()
	{
		return \core\url\UrlDecoder::getInstance()->getCurrenPageWithoutQueryString() == $this->getPath();
	}
	
	public function getShortClassName()
	{
		$className = str_replace(__NAMESPACE__, '', get_class($this));
		return lcfirst(str_replace('\Cabinet', '', $className));
	}
	
	abstract public function countNotifications();
	abstract public function countMessages();
	abstract public function exists();
}