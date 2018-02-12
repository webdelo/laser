<?php
namespace controllers\front\service;
use core\Configurator;

class RunLaserServiceFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\ControllersHandler,
		\core\traits\controllers\Templates;

	private $actionsRedirects = array(
		//'sitemap.xml' => 'sitemap',
		'robots.txt'  => 'robots',
	);

	public function __call($actionName, $arguments)
	{
		if (method_exists($this, $actionName))
			return call_user_func_array(array($this, $actionName), $arguments);
		elseif (isset($this->actionsRedirects[$actionName])){
			$action = $this->actionsRedirects[$actionName];
			return $this->$action();
		} else {
			$defaultControllerName = \core\Configurator::getInstance()->controllers->defaultFrontController;
			return $this->getController($defaultControllerName)->$actionName();
		}
	}

	public function redirect404()
	{
		header("HTTP/1.0 404 Not Found");
		$this->includeTemplate('404');
		die();
	}

	public function accessDenied($right)
	{
		$this->redirect404();
	}

	public function forbidden()
	{
		$this->redirect404();
	}

	protected function sitemap()
	{
		if ($this->getSERVER()['REQUEST_URI'] != '/sitemap.xml')
			return $this->redirect404();
		$sitemap = new \core\seo\sitemap\Sitemap();
		$articles = new \modules\articles\lib\Articles();
		$query = '
			AND `statusId` = ?d
			AND `categoryId` IN (?s)
			AND `categoryId` IN (
				SELECT
					`id`
				FROM
					`'.$articles->getCategories()->mainTable().'`
				WHERE
					`domainAlias` = "?s"
			)';
		$articles->setSubquery($query, \modules\articles\lib\ArticleConfig::ACTIVE_STATUS_ID, '57,102', $this->getCurrentDomainAlias());
		//$items = new \modules\catalog\items\lib\Catalog();
		//$items->setSubquery(' AND `statusId` NOT IN (?d, ?d)', \modules\catalog\items\lib\CatalogItemConfig::BLOCKED_STATUS_ID, \modules\catalog\items\lib\CatalogItemConfig::REMOVED_STATUS_ID);
		//$itemsCategories = $items->getCategories()->setSubquery(' AND `statusId` = ?d', 1);
		$sitemap->addObjects($articles)->printSitemap();
	}

	protected function robots()
	{
		if ($this->getSERVER()['REQUEST_URI'] != '/robots.txt')
			return $this->redirect404();
		
		/*$filePath = $this->isProductionDomain()
						? DIR.'/robots/'.$this->getDevelopersDomainAlias().'Robots.txt'
						: DIR.'/robots/DeveloperRobots.txt';
		*/
		$filePath = DIR.'/robots/'.$this->getDevelopersDomainAlias().'Robots.txt';

        $configurator = Configurator::getInstance();

        if ( $_SERVER['HTTP_HOST'] == \core\i18n\LangHandler::getInstance()->getLang() . '.' . $configurator->getArrayByKey('url')['default']['domain'] )
            $filePath = DIR.'/robots/'. ucfirst(\core\i18n\LangHandler::getInstance()->getLang()) . $this->getDevelopersDomainAlias().'Robots.txt';

		if (file_exists($filePath)){
			header('Content-type: text/plain');
			include($filePath);
		}else
			$this->redirect404();
	}

}