<?php
namespace interfaces;
interface ISitemap
{
    public function getPath(); // return String (URL to Object Page)
	public function getLastUpdateTime(); // return Timestamp
	public function getSitemapPriority(); // return Float (0.1-1.0)
	public function getChangeFreq(); // return String (always, hourly, daily, weekly, monthly, yearly, never)
}