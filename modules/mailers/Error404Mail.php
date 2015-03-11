<?php
namespace modules\mailers;
class Error404Mail extends \core\mail\MailBase
{
	use	\core\traits\RequestHandler,
		\core\traits\controllers\Authorization,
		\core\traits\ObjectPool;
	
	function __construct($data)
	{
		parent::__construct();
		$this->templates = DIR.'modules/orders/tpl/mailTemplates/';
		$this->data = $data;
	}
	
	public function send404ErrMail()
	{
		$mailerAddrs = \core\Configurator::getInstance()->getArraybyKey('debug');
		$res = $this->From($this->noreplyEmail)
					->To($mailerAddrs['mailTo'])
					->Subject("Обнаружена ошибка 404 на сайте " . $this->getCurrentDomainAlias())
					->Content('data', $this->data)
					->BodyFromFile('Error404.tpl')
					->Send();
		if($res)
			return 1;
		return ('Error mail() in '.get_class($this).'!');
	}
	
}