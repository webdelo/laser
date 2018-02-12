<?php
namespace modules\clients\components\clientSMS\lib;
class ClientSMS
{
	use \core\i18n\translator\TranslatorTrait;

	private $smsHandler;
	private $client;

	public function __construct(\modules\clients\lib\Client $client)
	{
		$this->client = $client;
		$this->getTranslator()->setLang($this->getClientLang());
	}

	protected function getClientLang()
	{
		return $this->getClient()->getSystemLang()->getAlias();
	}

	protected function getClient()
	{
		return $this->client;
	}

	protected function getSMSHandler()
	{
		if ( !$this->smsHandler ) {
			$this->smsHandler = new \modules\sms\SMSHandler;
		}

		return $this->smsHandler;
	}

	public function sendMessage($message)
	{
		$number = $this->getClient()->getMobile();
		if (empty($number))
			return false;
		return $this->getSMSHandler()->send($this->getClient()->getMobile(), $message);
	}

	public function sendSMSCode()
	{
		$message = $this->getTranslator()->get('confirmationNumberCode', $this->getClient()->getCodeFromPhone());
		return $this->sendMessage($message);
	}
}