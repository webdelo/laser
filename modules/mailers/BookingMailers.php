<?php
namespace modules\mailers;
class BookingMailers extends \core\mail\MailBase
{
	use \core\traits\RequestHandler,
		\core\traits\validators\Email;

//	private $managers = array('d.cercel@webdelo.org', 'a.popov@webdelo.org', 'taras.rozgon@bolgarskiydom.com');
	private $managers = array('d.cercel@webdelo.org');

	public function rules()
	{
		return array(
			'clientName, message' => array(
				'validation' => array('_validNotEmpty', array('Ф.И.О.', 'Сообщение')),
			),
			'email' => array(
				'validation' => array('_validEmail', array('notEmpty'=>true)),
			),
			'captcha' => array(
				'validation' => array('_validCorrectCaptcha', array('notEmpty'=>true)),
			),
		);
	}

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.\core\i18n\LangHandler::getInstance()->getLang().'/reservation/mailers/';
		$this->data = $data;
	}

	public function newBookingMailToOwner()
	{
		$client = $this->data->getOwner();
		if ($client->notSendBookingMails) {
			return 1;
		}

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Новая заявка на бронирование!')
				->Content('object', $this->data)
				->BodyFromFile('toOwner/newBooking.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function newBookingMailToClient()
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Ваша заявка успешно доставлена владельцу!')
				->Content('object', $this->data)
				->BodyFromFile('toClient/newBooking.tpl')
				->Send();

		$this->reset();
		
		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function confirmBooking()
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Владелец принял вашу заявку на бронирование!')
				->Content('object', $this->data)
				->BodyFromFile('toClient/bookingConfirm.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function refuseBooking($reason)
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$sameObjects = $this->data->getRealty()->getSameObjects();
		$sameObjects->setLimit(3);
		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Ваша заявка на бронирование отклонена')
				->Content('object', $this->data)
			    ->Content('reason', $reason)
				->Content('sameObjects', $sameObjects)
				->BodyFromFile('toClient/bookingRefuse.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function cancelBookingMailToOwner($reason)
	{
		$client = $this->data->getOwner();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Заказчик отменил бронирование!')
				->Content('object', $this->data)
			    ->Content('reason', $reason)
				->BodyFromFile('toOwner/bookingCancel.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function newMessage(\modules\messages\lib\Message $message)
	{
		$client = $this->getMessageMailReceiver($message);
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Новое сообщение по бронированию №'.$this->data->code)
				->Content('object', $this->data)
				->Content('message', $message)
				->BodyFromFile('newMessage.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function serviceMessageMail()
	{
		$res = $this->From($this->noreplyEmail)
					->To('d.cercel@webdelo.org')
					->Subject('Техническая поддержка сайта vput.ru')
					->Content('object', $this->data)
					->BodyFromFile('serviceMessage.tpl')
					->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function removeMessage(\modules\messages\lib\Message $message)
	{
		$client = $this->getMessageMailReceiver($message);
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Удалено сообщение по бронированию №'.$this->data->code)
				->Content('object', $this->data)
				->Content('message', $message)
				->BodyFromFile('removeMessage.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	private function getMessageMailReceiver(\modules\messages\lib\Message $message)
	{
		return ( $this->data->getOwner()->id == $message->getClient()->id )
			? $this->data->getClient()
			: $this->data->getOwner();
	}

	public function changeDatesMailToClient()
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Изменены даты в бронировани № '.$this->data->code)
				->Content('object', $this->data)
				->BodyFromFile('toClient/changeDates.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function changeDatesMailToOwner()
	{
		$client = $this->data->getOwner();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Изменены даты в бронировани № '.$this->data->code)
				->Content('object', $this->data)
				->BodyFromFile('toOwner/changeDates.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function surchage($price)
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Добавлена доплата в бронировани № '.$this->data->code)
				->Content('object', $this->data)
				->Content('price', $price)
				->BodyFromFile('toClient/surcharge.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function discount($price)
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Добавлена скидка в бронировани № '.$this->data->code)
				->Content('object', $this->data)
				->Content('price', $price)
				->BodyFromFile('toClient/discount.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function newInvoiceToClient(\modules\payments\invoices\lib\Invoice $invoice)
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Владелец выставил счет на оплату. Номер счета - #'.$this->data->code)
				->Content('object', $this->data)
				->Content('invoice', $invoice)
				->BodyFromFile('toClient/newInvoice.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function invoicePaidToOwner(\modules\payments\invoices\lib\Invoice $invoice)
	{
		$client = $this->data->getOwner();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Клиент успешно оплатил счет №'.$this->data->code)
				->Content('object', $this->data)
				->Content('invoice', $invoice)
				->BodyFromFile('toOwner/invoicePaid.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function invoicePaidToClient(\modules\payments\invoices\lib\Invoice $invoice)
	{
		$client = $this->data->getClient();
		if($client->notSendBookingMails)
			return 1;

		$res = $this->From($this->noreplyEmail)
				->To($client->getLogin())
				->Subject('['.SEND_FROM.'] Вы успешно оплатили счет №'.$this->data->code)
				->Content('object', $this->data)
				->Content('invoice', $invoice)
				->BodyFromFile('toClient/invoicePaid.tpl')
				->Send();

		$this->reset();

		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

}
