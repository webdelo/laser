<?php
namespace modules\mailers;
class MailGroupDriver extends \core\mail\MailBase
{
	use	\core\traits\RequestHandler,
		\core\traits\validators\Base;

	function __construct($data = null)
	{
		parent::__construct();
		$this->templates = DIR.'modules/orders/tpl/mailTemplates/';
		$this->data = $data;
	}

	public function rules()
	{
		return array(
			'subject, emails, text, ids' => array(
				'validation' => array('_validNotEmpty'),
			),
			'email' => array(
				'validation' => array('_validEmail',  array('not_empty'=>true)),
			),
		);
	}

	public function sendGroupDriver()
	{
		if (!$this->_beforeChange($this->data, array_keys($this->data)))
			return false;

		$emails = explode(',', str_replace(';', ',', str_replace(array("\r\n", "\n", "\t", ' ',), '', $this->data['emails'])));
		if(end($emails) == '')
			array_pop($emails);

		$bcc = explode(',', str_replace(';', ',', str_replace(array("\r\n", "\n", "\t", ' ',), '', $this->data['bcc'])));
		if(end($bcc) == '')
			array_pop($bcc);

		$this->data['text'] = str_replace("\r\n", "<br />", $this->data['text']);

		$res = $this->From($this->data['email'] ? $this->data['email'] : $this->noreplyEmail)
				->To($emails)
				->Bcc($bcc)
				->Subject($this->data['subject'])
				->Content('data', $this->data)
				->BodyFromFile('mailGroupDriver.tpl')
				->Send();

		if($res)
			return 1;
		throw new Exception('Error mail() in '.get_class($this).'!');
	}

}