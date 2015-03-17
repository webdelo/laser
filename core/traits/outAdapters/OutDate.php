<?php
namespace core\traits\outAdapters;
trait OutDate
{
	public function _outDate($data)
	{
		return \core\utils\Dates::convertDate($data, 'simple');
	}

	public function _outDateTime($data)
	{
		return \core\utils\Dates::toFullSimpleDate($data);
	}
	
	public function _outMonth($data)
	{
		return \core\utils\Months::getMonth($data);
	}
	
	public function _outMonthDeclensionUse($data)
	{
		return \core\utils\Months::getMonthDeclensionUse($data);
	}

}