<?php
// Interface for Shopcart Module objects
namespace interfaces;
interface IGoodForShopcart
{
	public function getMinQuantity(); // return Int
	public function getPriceByQuantity($quantity); // return Float
	public function getPriceByMinQuantity(); // return Float

	public function getName(); // return String

	public function getPathToShopcartGoodTemplate(); // return String

	public function getClass(); // return String
}