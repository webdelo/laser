<?php
namespace modules\passwordRecovery\lib;
class PasswordRecoveryMailers extends \core\mail\MailBase
{
	use \core\traits\RequestHandler,
		\core\traits\validators\Email,
		\core\i18n\translator\TranslatorTrait;

	function __construct(\modules\clients\lib\Client $data)
	{
		parent::__construct();
		$this->data = $data;
		$this->templates = DIR.'modules/passwordRecovery/tpl/'.$this->getClientLang().'/';
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

	public function sendResetPasswordEmail()
	{
		$res = $this->From($this->noreplyEmail)
				->To($this->data->getLogin())
				->Subject($this->getTranslator()->get('passwordUpdate', SEND_FROM))
				->Content('object', $this->data)
				->BodyFromFile('sendResetPasswordEmail.tpl')
				->Send();
		$this->reset();
		return $res;
	}
}