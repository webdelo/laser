<?php
namespace core\i18n\translator;
trait TranslateDataLocatorTrait
{
	private $_translator;

	private function getLangGettersObject()
	{
		$objectName = $this->getNamespaceToGetter().'\i18n\\'.$this->getClassName().'LangSource';
		return new $objectName();
	}

	private function getNamespaceToGetter()
	{
		$path = new \core\ArrayWrapper(explode('\\', get_class($this)));
		$path->pop(); // remove classname from path

		return '\\'.implode('\\', $path->getAsArray());
	}

	private function getClassName()
	{
		$path = new \core\ArrayWrapper(explode('\\', get_class($this)));
		$className = $path->pop();

		return $className;
	}
}

