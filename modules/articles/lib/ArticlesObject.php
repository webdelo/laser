<?php
namespace modules\articles\lib;
class ArticlesObject extends \core\modules\base\ModuleObjects
{
        use \core\traits\objects\WordsSearch;
        
	protected $configClass     = '\modules\articles\lib\ArticleConfig';
	protected $objectClassName = '\modules\articles\lib\Article';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}
