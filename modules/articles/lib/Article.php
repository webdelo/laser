<?php
namespace modules\articles\lib;
class Article extends \core\modules\base\ModuleObject implements \interfaces\IObjectToFrontend, \core\i18n\interfaces\Ii18n
{
	use \core\traits\ObjectPool,
		\core\modules\statuses\StatusTraitDecorator,
		\core\modules\categories\TranslateCategoryTraitDecorator,
		\core\modules\images\ImagesTraitDecorator,
		\core\i18n\TextLangParserTraitDecorator,
		\core\modules\filesUploaded\FilesTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\traits\ObjectLangTrait;

	protected $configClass = '\modules\articles\lib\ArticleConfig';

	public function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function edit($data = null, $fields = array(), $rules = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::edit($compacter->getPost(), $fields, $rules);
	}

	/* Start: Meta Methods */
	public function getMetaTitle($lang = null)
	{
		return $this->metaTitle
			? $this->getTextFromLangParser($this->metaTitle, $this->getLang($lang))
			: $this->getName();
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
		return $this->getTextFromLangParser($this->name, $this->getLang($lang));
	}

	public function getDescription($lang = null)
	{
		return $this->getTextFromLangParser($this->description, $this->getLang($lang));
	}

	public function getText($lang = null)
	{
		return $this->getTextFromLangParser($this->text, $this->getLang($lang));
	}
	/*   End: Main Data Methods */

	public function getH1($lang = null)
	{
		return $this->h1
			? $this->getTextFromLangParser($this->h1, $this->getLang($lang))
			: $this->getName($lang);
	}

	/* Start: URL Methods */
	public function getPath()
	{
		if ($this->alias == 'index')
			return '/';
		if ($this->categoryId == ArticleConfig::NEWS_CATEGORY_ID)
			return '/news/'.$this->alias.'/';
		if ($this->categoryId == ArticleConfig::NEWS_DEVICES_CATEGORY_ID)
			return '/news_devices/'.$this->alias.'/';
		if ($this->categoryId == ArticleConfig::RECOMMENDATIONS_CATEGORY_ID)
			return '/recommendations/'.$this->alias.'/';
		if ($this->categoryId == ArticleConfig::REVIEWS_CATEGORY_ID)
			return '/reviews/'.$this->alias.'/';
		if ($this->categoryId == ArticleConfig::INFORMATOR_ARTICLES_CATEGORY_ID)
			return '/articles/'.$this->alias.'/';
		return '/'.$this->alias.'/';
	}
	public function getAdminPath()
	{
		return '/admin/articles/article/'.$this->id.'/';
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

	public function isShowTitleArticle()
	{
		$config = $this->getConfig();
		return ! in_array($this->id, $config->notShowTitleArticlesId);
	}
}