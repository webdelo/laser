<?php
namespace core\seo\sitemap;
class SitemapValues
{
	static private $priority = array(
		'0.0',
		'0.1',
		'0.2',
		'0.3',
		'0.4',
		'0.5',
		'0.6',
		'0.7',
		'0.8',
		'0.9',
		'1.0'
	);
	
	static private $changeFreq = array(
		'always'  => 'Всегда',
		'hourly'  => 'Каждый час',
		'daily'   => 'Ежедневно',
		'weekly'  => 'Еженедельно',
		'monthly' => 'Ежемесячно',
		'yearly'  => 'Раз в год',
		'never'   => 'Никогда',
	);
	
	static public function getPriorityValues()
	{
		return self::$priority;
	}
	
	static public function getChangeFreqValues()
	{
		return self::$changeFreq;
	}
}