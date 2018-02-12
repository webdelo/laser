<?php
namespace controllers\front\cabinet\traits;
trait invoiceDetailsTrait
{
	protected function getInvoiceDetals()
	{
		return $this->includeTemplate('cabinet/invoices/invoice/invoiceDetails');
	}

	protected function getInvoicePayment()
	{
		return $this->includeTemplate('cabinet/invoices/invoice/invoicePayment');
	}
}