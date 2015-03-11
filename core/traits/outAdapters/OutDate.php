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
		return \core\utils\Dates::toDatetime($data);
	}

}