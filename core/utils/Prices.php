<?php
namespace core\utils;
class Prices
{
	public static function standartPrice($price){
		return number_format($price, 0, '.', ' ');
	}
}