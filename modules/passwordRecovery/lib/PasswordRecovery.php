<?php
namespace modules\passwordRecovery\lib;
class PasswordRecovery
{
	use \core\traits\Errors;

	private $object;

	public function __construct(\interfaces\IUserForAuthorization $object)
	{
		$this->setObject($object);
	}

	private function setObject(\interfaces\IUserForAuthorization $object)
	{
		$this->object = $object;
	}

	private function getObject()
	{
		return $this->object;
	}

	public function resetPasswordEmail()
	{
		$mailer = new \modules\passwordRecovery\lib\PasswordRecoveryMailers($this->getObject());
		return $mailer->sendResetPasswordEmail();
	}

	public function passwordRecovery($code, $password, $passwordConfirm)
	{
		if ( $code != $this->getObject()->getCodeFromPassword() ) {
			$this->addError('code', $this->getErrorsList()['code'][\core\i18n\LangHandler::getInstance()->getLang()]);
			return false;
		}

		$result = $this->getObject()->editPassword($password, $passwordConfirm);
		if ($result)
			return true;

		foreach ($this->getObject()->getErrors() as $field => $error)
			$this->addError($field, $error);
		return false;
	}
}
