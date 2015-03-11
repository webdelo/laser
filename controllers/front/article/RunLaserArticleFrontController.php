<?php
namespace controllers\front\article;
class RunLaserArticleFrontController extends \controllers\base\Controller
{
	use	\core\traits\Pager,
		\core\traits\controllers\Meta,
		\core\traits\controllers\Templates,
		\core\traits\controllers\ControllersHandler,
		\core\traits\controllers\RequestLevels,
		\core\traits\controllers\Breadcrumbs;

	private $articleObject;

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		if ($this->redirectToCatalogController())
			return;
		$this->defaultAction();
	}

	private function redirectToCatalogController()
	{
		if (isset($this->catalogRedirects[$this->getREQUEST()['action']])){
			$method = $this->catalogRedirects[$this->getREQUEST()['action']];
			if (isset($method)) {
				$this->catalogController->$method();
				return true;
			}
		}
		return false;
	}

	public function defaultAction()
	{
		if (!$this->action && $this->getSERVER()['REQUEST_URI']=='/')
			$this->action = 'viewIndex';
		if(isset ($this->getREQUEST()[0]))
			$this->action = $this->getREQUEST()[0];
		if ($this->actionExists($this->action)) {
			$action = $this->action;
			$this->$action();
		} else
			$this->viewArticle($this->action);
	}

	public function contacts()
	{
		$articles = new \modules\articles\lib\Articles();
		$article = $articles->getObjectByAlias('contacts');
		$this->setMetaFromObject($articles->getObjectByAlias('contacts'))
			 ->setContent('article', $article)
			 ->setLevel($article->getH1())
			 ->includeTemplate('articles/contacts');
	}

	public function viewArticle($alias)
	{
		if ($this->checkArticleAlias($alias)) {
			$articles = new \modules\articles\lib\Articles();
			$article = $articles->getObjectByAlias($alias);
			if ($this->checkDomainAlias($article)){
				if ($article->isValidPath($this->getSERVER()['REQUEST_URI']))
					return $this->setContent('article', $article)
								->setMetaFromObject($article)
								->setLevel($article->getH1())
								->includeTemplate('articles/article');
			}
		}
		$this->redirect404();
	}

	private function checkArticleAlias($alias)
	{
		return $this->getArticleObject()->checkAlias($alias);
	}

	private function checkDomainAlias($article)
	{
		return $article->getCategory()->domainAlias == $this->getCurrentDomainAlias();
	}

	public function viewIndex()
	{
		$this->setContent('article', $this->getArticle('index'))
			->setContent('wrGet', new \core\ArrayWrapper($this->getGET()))
			->includeTemplate('index');
	}

	public function getArticle ($alias) {
		$articles = new \modules\articles\lib\Articles();
		return new \modules\articles\lib\Article($articles->getIdByAlias($alias));
	}

	private function getArticleObject()
	{
		if (!isset($this->articleObject))
			$this->setArticlesObject();
		return $this->articleObject;
	}

	private function setArticlesObject()
	{
		$this->articleObject = new \modules\articles\lib\Articles();
	}

	public function getTopMenu()
	{
		$topMenu = $this->setMenuData(\modules\articles\lib\ArticleConfig::TOP_MENU_CATEGORY_ID, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID);
		$this->setContent('topMenu', $topMenu)
			 ->setContent('totalItems', $topMenu->count())
			 ->includeTemplate('articles/topMenu');
	}

	public function getFooterMenu()
	{
		$footerMenu = $this->setMenuData(\modules\articles\lib\ArticleConfig::TOP_MENU_CATEGORY_ID, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID);
		$this->setContent('footerMenu', $footerMenu)
			 ->setContent('totalItems', $footerMenu->count())
			 ->includeTemplate('articles/footerMenu');
	}

	private function setMenuData($category, $status)
	{
		$filters = new \core\FilterGenerator();
		$filters->setSubquery('AND mt.`categoryId` = ?d AND mt.`statusId` = ?d',$category,$status);
		$filters->setOrderBy('`priority` ASC');
		$articles = new \modules\articles\lib\Articles();
		$articles->setFilters($filters);
		return $articles;
	}

	public function getServicesMenu()
	{
		$servicesMenu = $this->setMenuData(\modules\articles\lib\ArticleConfig::SERVICES_MENU_CATEGORY_ID, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID);
		$this->setContent('servicesMenu', $servicesMenu)
			 ->includeTemplate('articles/servicesMenu');
	}

	public function getFooterServicesMenu()
	{
		$servicesMenu = $this->setMenuData(\modules\articles\lib\ArticleConfig::SERVICES_MENU_CATEGORY_ID, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID);
		$this->setContent('footerServicesMenu', $servicesMenu)
			 ->includeTemplate('articles/footerServicesMenu');
	}

//	public function news()
//	{
//		if ($this->isZeroRequestLevel()) {
//			$news = $this->setMenuData(\modules\articles\lib\ArticleConfig::NEWS_CATEGORY_ID, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID);
//			$this->setContent('objects', $news->setOrderBy('`date` ASC')->setQuantityItemsOnSubpageList(array(20))->setPager(2))
//				 ->setMetaFromObject($news->getCategories()->getObjectById(\modules\articles\lib\ArticleConfig::NEWS_CATEGORY_ID))
//				 ->includeTemplate('articles/news');
//		} elseif($this->isFirstRequestLevel()){
//			$this->viewArticle($this->getREQUEST()[0]);
//		} else {
//			$this->redirect404();
//		}
//	}

	public function articles()
	{
		$articleConf = new \modules\articles\lib\ArticleConfig();
		
		$objects = $this->getArticlesObject();
		$objects->resetFilters();
		$objects->setSubquery('AND `categoryId` = (?d)', 102)
				->setSubquery('AND `statusId` != (?d)', (int)$articleConf::BLOCKED_STATUS_ID)
				->setOrderBy('`date` DESC, `id` DESC');
		$this->setContent('articles', $objects)
			 ->setDescription('Статьи')
			 ->setKeywords('Статьи')
			 ->setLevel('Статьи')
			 ->includeTemplate('articles/articles');
	}

	public function getLeftNewsBlock()
	{
		$news = $this->setMenuData(\modules\articles\lib\ArticleConfig::NEWS_CATEGORY_ID, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID);
		$this->setContent('news', $news->setOrderBy('`date` DESC, `id` DESC')->setLimit(5))
			 ->includeTemplate('articles/newsBlock');
	}

	public function getArticlesForTeplitsyCategory()
	{
		$config = $this->getConfig();
		$objects = $this->getArticlesObject();
		$objects->resetFilters();
		return $objects->setSubquery('AND `categoryId` = (?d)', (int)$config::INFORMATOR_ARTICLES_TEPLIYSY_CATEGORY_ID)
					->setSubquery('AND `statusId` != (?d)', (int)$config::BLOCKED_STATUS_ID)
					->setOrderBy('`date` DESC, `id` DESC');
	}

	private function getConfig()
	{
		return $this->getObject('\modules\articles\lib\ArticleConfig');
	}

	private function getArticlesObject()
	{
		if (empty($this->articles))
			$this->articles = new \modules\articles\lib\Articles();
		return $this->articles;
	}
}
