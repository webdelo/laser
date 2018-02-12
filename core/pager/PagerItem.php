<?php
namespace core\pager;
class PagerItem
{

    use \core\traits\RequestHandler;

    private $page = null;
    private $currentPage = null;

    public function __construct($page = 1,$curentPage = 1)
    {
	$this->page = $page;
	$this->currentPage = $curentPage;
    }

    public function isCurrent(){
	return ($this->page == $this->currentPage);
    }

    public function getLink()
    {
	$link = $this->getSERVER()['REQUEST_URI'];
	$queryString = $this->getSERVER()['QUERY_STRING'];
	$linkWithoutQueryString = str_replace('?'.$queryString, '' ,$link);
	$getQueryConcatSymbol = empty($queryString) ? '?' : '&';
	$subpageLink = ($this->page == 1) ? '' : 'page='.$this->page;
	if (strstr($queryString,'page=')) {
		$queryString = str_replace('page='.$this->currentPage, $subpageLink, $queryString);
		$link_n = $linkWithoutQueryString .($queryString ? '?' : '') . rtrim($queryString, '&');
	} else
		$link_n = $link.$getQueryConcatSymbol.$subpageLink;

	return $link_n;
    }

    public function getPage()
    {
	return $this->page;
    }

	public function getCurrentPage()
	{
		return $this->currentPage;
	}

	public function isCurrentPage()
	{
		return $this->page == $this->currentPage   ?   true   :   false;
	}

}