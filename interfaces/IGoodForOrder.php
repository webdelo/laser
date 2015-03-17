<?php
// Interface for Order Module objects
namespace interfaces;
interface IGoodForOrder
{
	public function getMinQuantity(); // return Int
	public function getPriceByQuantity($quantity); // return Float
	public function getPriceByMinQuantity(); // return Float
	
	public function getBasePriceByQuantity($quantity); // return Float
	public function getBasePriceByMinQuantity(); // return Float
	
	public function getName(); // return String
	
	public function getPathToOrderGoodTemplate(); // return String
}