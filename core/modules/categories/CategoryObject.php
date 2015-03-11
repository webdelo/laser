<?php
namespace core\modules\categories;
class CategoryObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\core\modules\categories\CategoryConfig';
	
	function __construct($objectId, $configObject)
	{
	    parent::__construct($objectId, new $this->configClass($configObject));
	}
	
	public function getPath()
	{
	    return '/'.$this->getAliases();
	}
	
	private function getAliases()
	{
	    $parentId = $this->parentId;
	    $categoryId = $this->id;
	    $alias = $this->alias.'/';
	    while($parentId != 0){
		$result = \core\db\Db::getMysql()->rowAssoc('SELECT * FROM `'.$this->mainTable().'` WHERE `id` = ?d', array($parentId));
		$parentId = $result['parentId'];
		$categoryId = $result['id'];
		$alias = $result['alias'].'/'.$alias;
	    }
	    return $alias;
	}
}