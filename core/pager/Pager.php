<?php
namespace core\pager;
class Pager implements \Iterator
{

	use	\core\traits\RequestHandler,
		\core\traits\controllers\ControllersHandler,
		\core\traits\controllers\ServiceRequests;

	private $curentObject = null;
	private $objectsPerPage = null;
	private $totalObjects = null;
	private $var = array();
	private $config = array(
		"pagesBefore" => 3,
		"pagesAfter" => 3,
		"total" => false
	);

	public function __construct($config = array())
	{
		if(is_array($config) && !empty($config))
		$this->config = $config;
	}

	public function setData($objectsPerPage,$totalObjects,$curentObject = null)
	{
		if($curentObject == null)
		$this->curentObject = (empty($this->getGET()['page'])) ? 1 : $this->getGET()['page'];
		else
		$this->curentObject = $curentObject;

		$this->objectsPerPage = $objectsPerPage;
		$this->totalObjects = $totalObjects;
		$this->generatePager();

		if($this->getTotalPages() < $this->getGet()['page'])
			return $this->redirect404();
	}

	private function generatePager()
	{
		for($page = $this->getFirstPagerPage(); $page <= $this->getLastPagerPage();$page++)
		$this->var[$page] = new PagerItem($page,$this->curentObject);
	}

	public function getTotalPages()
	{
		return ceil($this->totalObjects / $this->objectsPerPage);
	}

	private function getFirstPagerPage()
	{
		return (($this->curentObject - $this->config['pagesBefore']) <= 0 || $this->config['total'])
		? 1
		: ($this->curentObject - $this->config['pagesBefore']);
	}

	private function getLastPagerPage()
	{
		return (($this->curentObject + $this->config['pagesAfter']) > $this->getTotalPages() || $this->config['total'])
		? $this->getTotalPages()
		: ($this->curentObject + $this->config['pagesAfter']);
	}

	public function getNextPage()
	{
		if($this->curentObject == $this->getTotalPages() || $this->config['total']){
		return false;
		}

		return new PagerItem($this->curentObject + 1,$this->curentObject);
	}

	public function getPrevPage()
	{
		if(($this->curentObject - 1) == 0 || $this->config['total'])
		return false;

		return new PagerItem($this->curentObject - 1,$this->curentObject);
	}

	public function getLastPage()
	{
		if($this->curentObject == $this->getTotalPages())
		return false;
		else
		return new PagerItem($this->getTotalPages(),$this->curentObject);
	}

	public function getFirstPage()
	{
		if($this->curentObject == 1 )
		return false;
		else
		return new PagerItem(1,$this->curentObject);
	}

	public function getLimit()
	{
		$get = $this->getGET();

		if(empty($get['page'])) {
		$offset = 0;
		$limit = $this->objectsPerPage;
		} else {
		$offset = ($this->curentObject - 1) * $this->objectsPerPage;
		$limit = $this->objectsPerPage;
		}

		return $offset.",".$limit;
	}

	public function getItemsOnPageLink($page)
	{
		if( strstr($this->getSERVER()['REQUEST_URI'], '?') ){
			if( strstr($this->getSERVER()['QUERY_STRING'], 'itemsOnPage=') )
				$link = '?'.str_replace ('itemsOnPage='.$this->getGET()['itemsOnPage'], 'itemsOnPage='.$page, $this->getSERVER()['QUERY_STRING']);
			else
				$link = $this->getSERVER()['REQUEST_URI'].'&itemsOnPage='.$page;
		}
		else
			$link = '?itemsOnPage='.$page;
		return $link;
	}

	/* Iterator Methods */
	public function rewind()
	{
		reset($this->var);
	}

	public function current()
	{
		return current($this->var);
	}

	public function key()
	{
		return key($this->var);
	}

	public function next()
	{
		return next($this->var);
	}

	public function valid()
	{
		$key = key($this->var);
		$var = ($key !== NULL && $key !== FALSE);
		return $var;
	}
	/* Iterator Methods */
}