<?php
namespace core\utils;
class Dates
{
	public static $secondsInDay = 86400;
	public static $dayWithout1Second = 86399;

	public static function convertDate($date, $type = 'mysql', $add_day = false)
	{
		if (empty($date)) return;
		if ($type == 'mysql') {
			$date = explode('-', $date);
			if (AMERICAN_DATE)
				$date = mktime(0, 0, 0, $date[0], ($add_day) ? $date[1]+1 : $date[1], $date[2]);
			else
				$date = mktime(0, 0, 0, $date[1], ($add_day) ? $date[0]+1 : $date[0], $date[2]);
		}
		if ($type == 'simple') {
			$date = date(SIMPLE_DATE_PATTERN, $date);
		}
		if ($type == 'fullSimple') {
			$date = date(SIMPLE_DATE_PATTERN.' H:i:s',$date);
		}
		if ($type == 'csv') {
			$dat[0] = substr($date, 0, 2);
			$dat[1] = substr($date, 2, 2);
			$dat[2] = substr($date, 4);
			$date = mktime(0, 0, 0, (int)$dat[0], (int)$dat[1], (int)$dat[2]);
		}
		if ($type == 'datetime') {
			$date = date('Y-m-d H:i:s',$date);
		}
		if ($type == 'fromDateTime') {
			$dates = explode(' ', $date);
			$times = explode(':', $dates[1]);
			$dates = explode('-', $dates[0]);

			$date = mktime($times[0], $times[1], $times[2], $dates[1], $dates[2], $dates[0]);
			$date = date(SIMPLE_DATE_PATTERN.' H:i:s', $date);
		}
		if ($type == 'fromDateTimeToTimestamp') {
			$dates = explode(' ', $date);
			$times = explode(':', $dates[1]);
			$dates = explode('-', $dates[0]);

			$date = mktime($times[0], $times[1], $times[2], $dates[1], $dates[2], $dates[0]);
		}

		return $date;
	}

	public static function getMonth($date)
	{
		$date = Dates::toTimestamp($date);
		$month = date('m',$date);
		return DateMonth::getMonth($month);
	}

	public static function getDay($date)
	{
		$date = Dates::toTimestamp($date);
		return date('d',$date);
	}

	public static function daysToUnix($data)
	{
		return $data * 86400 ;
	}

	public static function daysFromUnix($data)
	{
		return $data / 86400 ;
	}

	public static function daysBetweenDates($startDate, $endDate)
	{
		$startDate = Dates::toTimestamp($startDate);
		$endDate   = Dates::toTimestamp($endDate);
		return ceil(($endDate - $startDate)/Dates::$secondsInDay);
	}

	public static function isTimestamp($date)
	{
		return is_numeric($date);
	}

	public static function getDateMonthsLater($months, $date = null)
	{
		$timestamp = Dates::toTimestamp($date);
		$date = getdate($timestamp);
		$month = $date['mon']+$months;
		return mktime($date['hours'],$date['minutes'],$date['seconds'],$month,$date['mday'],$date['year']);
	}

	public static function toTimestamp($date = null, $defaultNow = false)
	{
		return (Dates::isTimestamp($date)) ? $date : ((isset($date)) ? Dates::convertDate($date) : (($defaultNow) ? time() : null));
	}

	public static function getDateDaysLater($days, $date = null)
	{
		$timestamp = Dates::toTimestamp($date);
		$date = getdate($timestamp);
		$day = $date_time_array['mday']+$days;
		return mktime($date['hours'],$date['minutes'],$date['seconds'],$date['mon'],$day,$date['year']);
	}

	public static function getCurrentSimpleDate()
	{
		return Dates::convertDate(time(), 'simple');
	}

	public static function getCurrentTimestampDate()
	{
		return Dates::toTimestamp(Dates::convertDate(time(), 'simple'));
	}

	public static function toSimpleDate($date = null)
	{
		$date = (!isset($date) || ($date instanceof \core\Noop)) ? time() : $date;
		return Dates::convertDate($date, 'simple');
	}

	public static function toFullSimpleDate($date)
	{
		return Dates::convertDate($date, 'fullSimple');
	}

	public static function toDatetime($date)
	{
		return (Dates::isTimestamp($date)) ? Dates::convertDate($date, 'datetime') : $date;
	}

	public static function dateTimeToTimestamp($date)
	{
		$date = (!isset($date) || ($date instanceof \core\Noop)) ? time() : $date;
		return strtotime($date);
	}

	public static function fromDatetime($date)
	{
		return Dates::convertDate($date, 'fromDateTimeToTimestamp');
	}

	public static function toSlashesFormat($date)
	{
		return str_replace('-', '/', $date);
	}

	public static function fromSlashesFormat($date)
	{
		return str_replace('/', '-', $date);
	}

	public static function daysInCurrentMonth()
	{
		return Dates::daysInMonth();
	}

	public static function daysInMonth($date = null)
	{
		$time = (empty($date)) ? time() : Dates::toTimestamp($date);
		return cal_days_in_month(CAL_GREGORIAN, date('m', $time), date('Y', $time));
	}

	public static function minutesFromSeconds($seconds)
	{
		return floor( $seconds / 60 );
	}

	public static function hoursFromSeconds($seconds)
	{
		return floor( Dates::minutesFromSeconds($seconds) / 60 );
	}

	public static function timeHMS($time)
	{
		$hours = floor($time/3600);
		$minutes = ($time/3600 - $hours)*60;
		$seconds = ceil(($minutes - floor($minutes))*60);
		return array('h'=>$hours, 'm'=>floor($minutes), 's'=> $seconds);
	}

	public static function  timeHMSstring($time)
	{
		$hms = \core\utils\Dates::timeHMS($time);
		$string = '';
		if (!empty($hms['h']))
			$string .=$hms['h'].' ч. ';

		if (!empty($hms['m']))
			$string .=$hms['m'].' м. ';
		else
			if (!empty($hms['s']))
				$string .= $hms['s'].' с.';

		return $string;
	}
}
?>