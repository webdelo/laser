<?php
namespace modules\articles\lib;
class ArticleObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\articles\lib\ArticleConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}