<?php
namespace core\traits\validators;
trait Date
{
	public function _validMonth($data)
	{
		return ( !empty($data) && $data <= 12 );
	}
	
	public function _validDay($data, $settings)
	{
		return ( !empty($data) && $data <= \core\utils\Months::getMonthsSizeByMonth($this->data[$settings['month']]) );
	}
	
	public function _validDate($data)
	{
		$patern = "/[\d]{2}-[\d]{2}-[\d]{4}/";
		if (empty($data) || !preg_match($patern, $data)) return false;
		return true;
	}

	public function _validDepDate($data)
	{
		$patern = "/[\d]{2}-[\d]{2}-[\d]{4}/";
		if (empty($data) || !preg_match($patern, $data) || \core\utils\Dates::convertDate($data) <= time() + 86400) return false;
		return true;
	}
}