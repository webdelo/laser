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
	
	public static function getMonth($monthNumber)
	{
		if(isset(DateMonth::$monthes[$monthNumber]))
			return DateMonth::$monthes[$monthNumber];
		else
			return false;
	}
}