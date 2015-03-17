<?php
namespace modules\clients\components\clientMailers\lib;
class ClientMailers extends \core\mail\MailBase
{
	use \core\traits\RequestHandler,
		\core\traits\validators\Email,
		\core\i18n\translator\TranslatorTrait;

	function __construct(\modules\clients\lib\Client $data)
	{
		parent::__construct();
		$this->data = $data;
		$this->templates = DIR.'modules/clients/components/clientMailers/tpl/'.$this->getClientLang().'/';
		$this->getTranslator()->setLang($this->getClientLang());
	}

	protected function getClientLang()
	{
		return $this->getClient()->getSystemLang()->getAlias();
	}

	protected function getClient()
	{
		return $this->data;
	}

	public function sendEmailCode()
	{
		$res = $this->From($this->noreplyEmail)
					->To($this->getClient()->getLogin())
					->Subject($this->getTranslator()->get('confirmationMail', SEND_FROM))
					->Content('object', $this->getClient())
					->BodyFromFile('sendEmailCode.tpl')
					->Send();

		$this->reset();
		return $res;
	}

	public function sendResetPasswordEmail()
	{
		$res = $this->From($this->noreplyEmail)
					->To($this->getClient()->getLogin())
					->Subject($this->getTranslator()->get('resetPassword', SEND_FROM))
					->Content('object', $this->getClient())
					->BodyFromFile('sendResetPasswordEmail.tpl')
					->Send();

		$this->reset();
		return $res;
	}
}