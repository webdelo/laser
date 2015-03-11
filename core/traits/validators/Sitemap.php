<?php
namespace core\traits\validators;
trait Sitemap
{
	public function _validLastUpdateTime($data)
	{
		if (empty ($data) || \core\utils\Dates::convertDate($data, 'mysql') <= time()) 
			return true;
		return false;
	}
	
	public function _validSitemapPriority($data)
	{
		return in_array($data, \core\seo\sitemap\SitemapValues::getPriorityValues());
	}
	
	public function _validChangeFreq($data)
	{
		return array_key_exists($data, \core\seo\sitemap\SitemapValues::getChangeFreqValues());
	}
}