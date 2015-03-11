<?php
namespace core\traits\adapters;
trait Sitemap
{
	
	public function _adaptLastUpdateTime($key)
	{
		$this->data[$key] = (empty($this->data[$key])) ? time() : \core\utils\Dates::toTimestamp($this->data[$key]);
	}
	
	public function _adaptSitemapPriority($key)
	{
		$this->data[$key] = (empty($this->data[$key])) ? '0.5' : $this->data[$key];
	}
	
	public function _adaptChangeFreq($key)
	{
		$this->data[$key] = (empty($this->data[$key])) ? 'weekly' : $this->data[$key];
	}
}