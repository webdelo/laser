<?php
namespace interfaces;
interface IPrices
{
	public function getMinPrice(); // return IPrice object
	public function getMinQuantity(); // return Int
	public function getPriceByQuantity($quantity); // return IPrice object
}