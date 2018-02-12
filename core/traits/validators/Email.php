<?php
namespace core\traits\validators;
trait Email
{
	public function _validEmail($data, $settings = array())
	{
		$settings['key'] = (empty($settings['key'])) ? 'email' : $settings['key'];
		if (empty($data) && !empty($settings['notEmpty'])) {
			$this->errors[$settings['key']] = $this->errorsList['email_empty'];
			return 'error_add';
		}
		if (!empty($data) && !\core\utils\Utils::isEmail($data)) {
			$this->errors[$settings['key']] = $this->errorsList['email_invalid'];
			return 'error_add';
		}
		return true;
	}
}