<?php
namespace modules\fabricators\lib;
class Fabricator extends \core\modules\base\ModuleDecorator implements \interfaces\IObjectToFrontend
{
	function __construct($objectId)
	{
		$object = new FabricatorObject($objectId);
		$object = new \core\modules\categories\CategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \modules\parameters\components\parametersValues\lib\ParametersValuesRelationDecorator($object);
		$object = new \modules\properties\components\propertiesValues\lib\RelationsDecorator($object);

		parent::__construct($object);
	}

	public function edit ($data, $fields = array())
	{
		if ( $this->getParameters()->edit($data->parametersValues) )
//			if ( $this->additionalCategories->edit($data->additionalCategories) )
				return $this->getParentObject()->edit($data, $fields);

		return false;
	}

	/* Start: Meta Methods */
	public function getMetaTitle()
	{
		return $this->metaTitle ? $this->metaTitle : $this->getName();
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

	public function getH1()
	{
		return empty($this->h1) ? $this->name : $this->h1;
	}

	/* Start: URL Methods */
	public function getPath()
	{
		if ($this->alias == 'index')
			return '/';
		return '/'.$this->alias.'/';
	}
	/*   End: URL Methods */

	public function isValidPath($path)
	{
		return $this->getPath() == rtrim($path,'/').'/';
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	/* Start: Sitemap Methods */
	public function getLastUpdateTime()
	{
		return empty($this->lastUpdateTime) ? time() : $this->lastUpdateTime;
	}

	public function getSitemapPriority()
	{
		return empty($this->sitemapPriority) ? '0.5' : $this->sitemapPriority;
	}

	public function getChangeFreq()
	{
		return empty($this->changeFreq) ? 'weekly' : $this->changeFreq;
	}
	/*   End: Sitemap Methods */

	public function isShowTitleFabricator()
	{
		$config = $this->getConfig();
		return ! in_array($this->id, $config->notShowTitleFabricatorsId);
	}
}