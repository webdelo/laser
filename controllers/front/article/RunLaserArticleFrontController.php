<?php
namespace controllers\front\article;
class RunLaserArticleFrontController extends \controllers\base\Controller
{
	use	\core\traits\Pager,
		\core\traits\controllers\Meta,
		\core\traits\controllers\Templates,
		\core\traits\controllers\ControllersHandler,
		\core\traits\controllers\RequestLevels,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\RequestHandler,
		\core\traits\ObjectPool;

	private $articleObject;

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
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
		if (!$this->action && $this->requestWithoutParameters() == '/' )
			$this->action = 'viewIndex';
		if(isset ($this->getREQUEST()[0]))
			$this->action = $this->getREQUEST()[0];
		if (stripos($this->getSERVER()['REQUEST_URI'], "?lang=") > 0) {
			$articles = new \modules\articles\lib\Articles();
			$article = $articles->getObjectByAlias('index');
			header("Location: " . "http://" . $this->getSERVER()['HTTP_HOST']);
			die();
		}
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
	
	public function getMainArticleName()
	{
		//$articles = new \modules\articles\lib\Articles();
		//$article = $articles->getObjectByAlias('index');
		
		$article = $this->getArticle('index');
		return $article->getName();
	}
	
	public function viewArticle($alias)
	{
		if ($this->checkArticleAlias($alias)) {
			$articles = new \modules\articles\lib\Articles();
			$article = $articles->getObjectByAlias($alias);
			if ($this->checkDomainAlias($article)){
				if ($article->isValidPath($this->getSERVER()['REQUEST_URI']))
					$newArticle = $this->calcPrices($article);
                    $newArticle ? $newArticle : $this->redirect404();
					//$isGameplay = $alias == 'game-process' ? false : true;
					$isGameplay = $this->belongsToGameProcessCategory($article);
				
					return $this->setContent('article', $newArticle)
								->setContent('showImages', $isGameplay)
								->setMetaFromObject($article)
								->setLevel($article->getH1())
								->includeTemplate('articles/article');
			}
		}
		$this->redirect404();
	}

	private function belongsToGameProcessCategory($article)
	{
		$articleCategoryId = $article->categoryId;
		if ($articleCategoryId == 103) { return true; } else { return false; } //103 - projects category id
	}

	private function calcPrices($article)
	{
		$currency = $article->getCurrency();
		
		$pos = strpos($article->getText(), '$s_full_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('s_full');
			$article->text = str_replace('$s_full_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$s_minimum_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('s_minimum');
			$article->text = str_replace('$s_minimum_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$m_full_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('m_full');
			$article->text = str_replace('$m_full_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$m_minimum_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('m_minimum');
			$article->text = str_replace('$m_minimum_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$l_full_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('l_full');
			$article->text = str_replace('$l_full_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$l_minimum_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('l_minimum');
			$article->text = str_replace('$l_minimum_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$xl_full_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('xl_full');
			$article->text = str_replace('$xl_full_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$xl_minimum_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('xl_minimum');
			$article->text = str_replace('$xl_minimum_rub',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$bb_desk_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('bb_desk');
			$article->text = str_replace('$bb_desk_rub',$calc,$article->getText());
		}

		$pos = strpos($article->getText(), '$bb_rocket_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('bb_rocket');
			$article->text = str_replace('$bb_rocket_rub',$calc,$article->getText());
		}

		$pos = strpos($article->getText(), '$climbing_wall_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('climbing_wall');
			$article->text = str_replace('$climbing_wall_rub',$calc,$article->getText());
		}

		$pos = strpos($article->getText(), '$viktorinye_stoiki_rub');
		if ($pos === false) {} else {
			$calc = $article->getEquipmentPrice('viktorinye_stoiki');
			$article->text = str_replace('$viktorinye_stoiki_rub',$calc,$article->getText());
		}
		
		
		
		$pos = strpos($article->getText(), '$s_full');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('s_full'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$s_full',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$s_minimum');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('s_minimum'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$s_minimum',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$m_full');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('m_full'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$m_full',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$m_minimum');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('m_minimum'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$m_minimum',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$l_full');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('l_full'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$l_full',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$l_minimum');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('l_minimum'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$l_minimum',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$xl_full');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('xl_full'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$xl_full',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$xl_minimum');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('xl_minimum'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$xl_minimum',$calc,$article->getText());
		}

		$pos = strpos($article->getText(), '$bb_desk');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('bb_desk'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$bb_desk',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$bb_rocket');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('bb_rocket'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$bb_rocket',$calc,$article->getText());
		}
		
		$pos = strpos($article->getText(), '$climbing_wall');
		if ($pos === false) {} else {
			$ep = str_replace(' ', '', $article->getEquipmentPrice('climbing_wall'));
			$calc = round((int)$ep / $currency);
			$article->text = str_replace('$climbing_wall',$calc,$article->getText());
		}
		
		
		return $article;
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
		$alias = 'index';
		$filters = new \core\FilterGenerator();
		$filters->setSubquery('AND mt.`alias` = \'?s\'',$alias);
		$articles = new \modules\articles\lib\Articles();
		$articles->setFilters($filters);

		$article = $articles->current();
		$newArticle = $this->calcPrices($article);
		$this->setContent('article', $article)
			 ->setMetaFromObject($article)
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

	protected function getExchangeRate()
	{
		$settings = new \core\Settings();
		return $settings->getSettings('*',[])['rate'];
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

	private function requestWithoutParameters()
    {
        return strtok($_SERVER["REQUEST_URI"],'?');
    }
}
