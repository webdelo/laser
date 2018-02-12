<?php
namespace interfaces;
interface IOrderGoods extends \Iterator
{
	public function getTotalGoodsQuantity(); // return Int
	public function getTotalSum(); // return Float
	public function getTotalDiscount(); // return Float
}