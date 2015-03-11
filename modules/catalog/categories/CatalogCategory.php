<?php
namespace modules\catalog\categories;
class CatalogCategory extends \core\modules\base\ModuleDecorator implements \interfaces\IObjectToFrontend
{
	function __construct($objectId, $configObject)
	{
		$object = new CatalogCategoryObject($objectId, $configObject);
		$object = new \core\modules\base\ParentDecorator($object, $configObject);
		$object = new \modules\parameters\lib\ParametersRelationDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		parent::__construct($object);
	}

	public function getParametersCategory ()
	{
		$parameters = new \modules\parameters\lib\Parameters();
		return (int)$this->parametersCategoryId
			? $parameters->getCategories()->getObjectById((int)$this->parametersCategoryId)
			: $this->getNoop();
	}

	function edit($post, $fields=array())
	{
		return ($this->getParametersRelation()->edit($post->parameters)) ? $this->getParentObject()->edit($post, $fields) : false;
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
		return $this->name;
	}
	/*   End: Main Data Methods */

	/* Start: URL Methods */
	public function getPath()
	{
		$categoryRules = new \core\modules\categories\CategoriesAliasesRules;
		return $categoryRules->useRules($this->getParentObject()->getPath());
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