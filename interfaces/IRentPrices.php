<?php
namespace interfaces;
interface IRentPrices
{
	public function getDayMinPrice(); // return IPrice object
	public function getWeekMinPrice(); // return IPrice object
	public function getMonthMinPrice(); // return IPrice object
	
	public function getMinQuantity(); // return Int
	public function getPriceByQuantity($quantity); // return IPrice object
}