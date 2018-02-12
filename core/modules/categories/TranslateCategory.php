<?php
namespace core\modules\categories;
class TranslateCategory extends \core\modules\base\ModuleObject implements \interfaces\IObjectToFrontend, \core\i18n\interfaces\Ii18n
{
	use \core\traits\ObjectPool,
		\core\modules\base\ParentTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\TextLangParserTraitDecorator,
		\core\modules\statuses\StatusTraitDecorator,
		\core\i18n\traits\ObjectLangTrait;

	protected $configClass = '\core\modules\categories\TranslateCategoryConfig';

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

	public function add($data = null, $fields = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::add($compacter->getPost(), $fields);
	}
	
	public function edit($data = null, $fields = array(), $rules = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::edit($compacter->getPost(), $fields, $rules);
	}

	/* Start: Meta Methods */
	public function getMetaTitle($lang = null)
	{
		return $this->getTextFromLangParser($this->metaTitle, $this->getLang($lang));
	}

	public function getMetaDescription($lang = null)
	{
		return $this->getTextFromLangParser($this->metaDescription, $this->getLang($lang));
	}

	public function getMetaKeywords($lang = null)
	{
		return $this->getTextFromLangParser($this->metaKeywords, $this->getLang($lang));
	}

	public function getHeaderText($lang = null)
	{
		return $this->getTextFromLangParser($this->headerText, $this->getLang($lang));
	}
	/*   End: Meta Methods */

	/* Start: Main Data Methods */
	public function getName($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['name'], $this->getLang($lang));
	}

	public function getDescription($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['description'], $this->getLang($lang));
	}

	public function getText($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['text'], $this->getLang($lang));
	}

	public function getH1($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['h1'], $this->getLang($lang));
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