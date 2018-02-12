<?php
namespace core\traits\validators;
trait Price
{
	public function _validPrice($data)
	{
//		if (empty ($data) || !preg_match("/^[\d]{1,8}[.][\d]{2}$/", $data)) return false;
//		return true;

		if(!isset($data))
			return false;
		if(!is_numeric($data))
			return false;
		if($data <= 0)
			return false;

		return true;
	}

	public function _validCost($data)
	{
		if (!empty($data)) {
			if(!is_numeric($data))
				return false;
			if($data < 0)
				return false;
		}

		return true;
	}

	public function _validAdditionalPrice($data)
	{
		if (!empty($data) && is_numeric($data)) {
				return true;
		}
		return false;
	}
}