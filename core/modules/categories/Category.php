<?php
namespace core\modules\categories;
class Category extends \core\modules\base\ModuleDecorator implements \interfaces\IObjectToFrontend
{
	function __construct($objectId, $configObject)
	{
		$object = new CategoryObject($objectId, $configObject);
		$object = new \core\modules\base\ParentDecorator($object, $configObject);
		$object = new \core\modules\statuses\StatusDecorator($object);
		parent::__construct($object);
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
		$categoryRules = new CategoriesAliasesRules;
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

	public function getH1()
	{
		return empty($this->h1) ? $this->name : $this->h1;
	}
}