<?php
namespace core\traits\validators;
trait Date
{
	protected function _validDate($data)
	{
		$patern = "/[\d]{2}-[\d]{2}-[\d]{4}/";
		if (empty($data) || !preg_match($patern, $data)) return false;
		return true;
	}

	protected function _validDepDate($data)
	{
		$patern = "/[\d]{2}-[\d]{2}-[\d]{4}/";
		if (empty($data) || !preg_match($patern, $data) || \core\utils\Dates::convertDate($data) <= time() + 86400) return false;
		return true;
	}
}