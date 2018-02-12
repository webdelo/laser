<?php
namespace interfaces;
interface ICatalogGood extends IGoodForShopcart, IGoodForOrder
{
	public function getCode(); // return string
	public function getClass(); // return string
	public function getName(); // return string
	public function getDescription(); // return string
}