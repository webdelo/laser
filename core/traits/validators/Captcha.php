<?php
namespace core\traits\validators;
trait Captcha
{
	protected function _validCorrectCaptcha($data)
	{
		$captcha = new \core\captcha\CaptchaString();
		if (!$captcha->checkCaptcha($data)) {
			$this->errors['captcha'] = $this->errorsList['captcha'];
			return 'error_add';
		}
		return true;
	}
}