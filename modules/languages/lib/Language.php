<?php
namespace modules\languages\lib;
class Language extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\languages\lib\LanguageConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getInitName()
	{
		return $this->initName;
	}

	public function getAlias()
	{
		return $this->alias;
	}
}