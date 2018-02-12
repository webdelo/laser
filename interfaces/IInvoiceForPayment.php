<?php
namespace interfaces;
interface IInvoiceForPayment extends IObservable
{
	public function getCode(); // return string
	public function getDescription(); // return string
	public function getTotalSum(); // return string
	public function isActualForPayment(); // return Boolean
	public function isPaid(); // return Boolean
	public function isUnpaid(); // return Boolean
	public function wasPaid($description = null); // return $this
}