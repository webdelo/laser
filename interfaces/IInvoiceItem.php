<?php
namespace interfaces;
interface IInvoiceItem
{
	public function getPrice(); // return string
	public function getName(); // return string
	public function getDescription(); // return string
	public function getType(); // return string
}