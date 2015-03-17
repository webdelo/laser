<?php
namespace interfaces;
interface IRentPrice
{
	public function getPeriodName(); // return String
	public function getStartPeriodDate(); // return Date
	public function getEndPeriodDate(); // return Date
	public function getDayPrice(); // return Float
	public function getWeekendPrice(); // return Float
	public function getMonthPrice(); // return Float
}