<?php
namespace core\utils;
class DateMonth
{
	protected static $monthes = array(
		'01' => 'ЯНВ.',
		'02' => 'ФЕВ.',
		'03' => 'МАР.',
		'04' => 'АПР.',
		'05' => 'МАЯ',
		'06' => 'ИЮН.',
		'07' => 'ИЮЛ.',
		'08' => 'АВГ.',
		'09' => 'СЕН.',
		'10' => 'ОКТ.',
		'11' => 'НОЯ.',
		'12' => 'ДЕК.',
	);

	protected static $monthesFullGenetive = array(
		'01' => 'января',
		'02' => 'февраля',
		'03' => 'марта',
		'04' => 'апреля',
		'05' => 'мая',
		'06' => 'июня',
		'07' => 'июля',
		'08' => 'августа',
		'09' => 'сентября',
		'10' => 'октября',
		'11' => 'ноября',
		'12' => 'декабря',
	);

	public static function getMonth($monthNumber)
	{
		if(isset(DateMonth::$monthes[$monthNumber]))
			return DateMonth::$monthes[$monthNumber];
		else
			return false;
	}

	public static function getMonthFullGenetive($monthNumber)
	{
		if(isset(DateMonth::$monthesFullGenetive[$monthNumber]))
			return DateMonth::$monthesFullGenetive[$monthNumber];
		else
			return false;
	}
}