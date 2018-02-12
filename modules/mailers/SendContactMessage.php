<?php
namespace modules\mailers;
class SendContactMessage extends \core\mail\MailBase
{
	use \core\traits\validators\Base,
		\core\traits\validators\Captcha;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.$_REQUEST['lang'].'/emails/';
		$this->data = $data->getArray();
	}

	public function rules()
	{
		return array(
			'msgName' => array(
				'validation' => array('_validNotEmpty', array('Телефон')),
			),
			'email' => array(
				'validation' => array('_validEmail', array('notEmpty'=>true)),
			),
			'msgText' => array(
				'validation' => array('_validNotEmpty', array('Сообщение')),
			),
		);
	}

	public function sendMessage()
	{
		if (!$this->_beforeChange($this->data, array_keys($this->data)))
			return false;
		$res = $this->From($this->noreplyEmail)
				->To($this->adminEmail)
				->Bcc($this->bccEmail)
				->Subject('Новое сообщение с сайта  '.SEND_FROM)
				->Content('data', $this->data)
				->BodyFromFile('contactForm.tpl')
				->Send();
		return $res;
	}
}
