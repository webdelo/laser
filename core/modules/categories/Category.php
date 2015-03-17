<?php
namespace core\modules\categories;
class Category extends \core\modules\base\ModuleObject implements \interfaces\IObjectToFrontend
{
	use \core\traits\ObjectPool,
		\core\modules\base\ParentTraitDecorator,
		\core\modules\statuses\StatusTraitDecorator;

	protected $configClass = '\core\modules\categories\CategoryConfig';

	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
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

		/* Start: Meta Methods */
	public function getMetaTitle()
	{
		return $this->metaTitle;
	}

	public function getMetaDescription()
	{
		return $this->metaDescription;
	}

	public function getMetaKeywords()
	{
		return $this->metaKeywords;
	}

	public function getHeaderText()
	{
		return $this->headerText;
	}
	/*   End: Meta Methods */

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->loadObjectInfo()->objectInfo['name'];
	}
	/*   End: Main Data Methods */

	/* Start: URL Methods */
	public function getPath()
	{
		$categoryRules = new CategoriesAliasesRules;
		return $categoryRules->useRules('/'.$this->getAliases());
	}
	/*   End: URL Methods */

	/* Start: Sitemap Methods */
	public function getLastUpdateTime()
	{
		return time();
	}

	public function getSitemapPriority()
	{
		return '0.7';
	}

	public function getChangeFreq()
	{
		return 'daily';
	}
	/*   End: Sitemap Methods */
}