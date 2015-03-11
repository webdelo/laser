<?php
namespace exceptions;
class ExceptionShopcart extends \Exception
{
	private $quantity;

	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
		return $this;
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function getTextError($lang = 'ru')
	{
		$errorsList = new \modules\shopcart\lib\ShopcartErrors;
		$errorText = $errorsList->errors[$lang][$this->getCode()];
		$errorText = isset($errorText) ? $errorText : $this->getMessage();
		return str_replace('%quantity%', $this->getQuantity(), $errorText);
	}
};