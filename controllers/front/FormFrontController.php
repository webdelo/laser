<?php
namespace controllers\front;
class FormFrontController extends \controllers\base\Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __call($name, $arguments)
	{
		$this->defaultAction();
	}

	public function defaultAction()
	{
		
	}
	
	public function order()
	{		
		$mail = new MailOrders($this->getPOST());
		$this->setObject($mail)->ajax($mail->sendOrder(), 'ajax');
	}
	
	public function contacts()
	{
		$mail = new MailContacts($this->getPOST());
		$this->setObject($mail)->ajax($mail->sendContacts(), 'ajax');
	}
}