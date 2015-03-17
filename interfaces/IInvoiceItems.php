<?php
namespace interfaces;
interface IInvoiceItems extends \Iterator
{
	public function setFiltersByType($type = 'all'); // return $this
	public function simpleAdd($name, $price, $type, $description = null); // return boolean
	public function getTotalSum(); // return float
}