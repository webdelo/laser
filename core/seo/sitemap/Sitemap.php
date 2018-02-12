<?php
namespace core\seo\sitemap;
class Sitemap
{
	private $elements = array();
	private $domain;
	
	public function __construct($domain = null)
	{
		$this->setDomain($domain);
	}
	
	private function setDomain($domain)
	{
		$this->domain = empty($domain) ? $this->getDefaultDomain() : $domain;
		return $this;
	}
	
	private function getDefaultDomain()
	{
		return 'https://'.$_SERVER['HTTP_HOST'];
	}
	
	public function addObjects($objects) 
	{
		if ($objects instanceof \Iterator){
			foreach($objects as $object)
				$this->addObject($object);
			return $this;
		}
		throw new \Exception('Object '.get_class($objects).' not implement interface Iterator in class '.get_class($this).'!');
	}
	
	public function addObject($object) 
	{
		if ($object instanceof \interfaces\IObjectToFrontend){
			return $this->addData($object->getPath(), $object->getSitemapPriority(), \core\utils\Dates::toDatetime($object->getLastUpdateTime()), $object->getChangeFreq());
		}
		throw new \Exception('Object '.get_class($object).' not implement interface IObjectToFrontend in class '.get_class($this).'!');
	}
	
	public function addData($loc, $priority = null, $lastmod = null, $changefreq = null)
	{
		$this->elements[] = array(
			'loc'        => $loc,
			'priority'   => $priority,
			'lastmod'    => $lastmod,
			'changefreq' => $changefreq,
		);
		return $this;
	}

	public function printSitemap() 
	{
		echo $this->printXMLHeaders()
				  ->getSitemapCode();
	}
	
	private function printXMLHeaders()
	{
		header("Content-Type: text/xml");
		header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0,pre-check=0");
		header("Cache-Control: max-age=0");
		header("Pragma: no-cache");
		return $this;
	}
	
	private function getSitemapCode()
	{
		return $this->getSitemapHeader().$this->getSitemapBody().$this->getSitemapFooter();
	}
	
	private function getSitemapHeader()
	{
		return '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	}
	
	private function getSitemapBody()
	{
		$sitemap = '';
		foreach($this->elements as $element)
			$sitemap .= $this->getElementCode($element);
		return $sitemap;
	}
	
	private function getElementCode($element)
	{
		$code = '<url>';
		$code.='<loc>'.$this->domain.$element['loc'].'</loc>';
		if ($element['priority'])
			$code.='<priority>'.$element['priority'].'</priority>';
		if ($element['lastmod'])
			$code.='<lastmod>'.$element['lastmod'].'</lastmod>';
		if ($element['changefreq'])
			$code.='<changefreq>'.$element['changefreq'].'</changefreq>';
		$code .= '</url>';
		return $code;
	}
	
	private function getSitemapFooter()
	{
		return '</urlset>';
	}
	
	public function resetSitemap() 
	{
		$this->elements = array();
		return $this;
	}
}