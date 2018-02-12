<?php
namespace core\utils;
class Months
{
	private static $months = array(
		'Январь', 'Фераль',
		'Март', 'Апрель', 'Май',
		'Июнь', 'Июль', 'Август',
		'Сентябрь', 'Октябрь', 'Ноябрь',
		'Декабрь',
		
	);
	
	private static $monthsDeclension = array(
		'января', 'фераля',
		'марта', 'апреля', 'мая',
		'июня', 'июля', 'августа',
		'сентября', 'октября', 'ноября',
		'декабря',
		
	);
	
	private static $monthsSize = array(
		'31', '29',
		'31', '30', '31',
		'30', '31', '31',
		'30', '31', '30',
		'31', 
		
	);

	public static function getMonths()
	{
		return self::$months;
	}
	
	public static function getMonth($key)
	{
		return self::getMonths()[(int)$key-1];
	}
	
	public static function getMonthsDeclension()
	{
		return self::$monthsDeclension;
	}
	
	public static function getMonthDeclensionUse($key)
	{
		return self::getMonthsDeclension()[(int)$key-1];
	}
	
	public static function getMonthsSize()
	{
		return self::$monthsSize;
	}
	
	public static function getMonthsSizeByMonth($month)
	{
		return self::getMonthsSize()[(int)$month-1];
	}
	
	public static function getDaysByMonth($month) 
	{
		$days = array();
		$day = 1;
		while ($day <= self::getMonthsSizeByMonth($month)) {
			$days[] = $day++;
		}
		return $days;
	}
}