<?php
namespace interfaces;
interface IInvoice extends IInvoiceForPayment
{
	public function getDate(); // return DateTime
	public function getItems(); // return IInvoiceItems
}