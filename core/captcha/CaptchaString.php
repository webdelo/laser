<?php
namespace core\captcha;
class CaptchaString
{
	use \core\traits\RequestHandler;

	protected $num1 = 1;
	protected $num2 = 1;
	protected $sign = '+';
	protected $result = null;

	public function generate()
	{
		$signs = array('+', '-');
		$this->num1 =  mt_rand(1,9);
		$this->num2 =  mt_rand(1,9);
		$this->sign =  $signs[array_rand($signs)];

	if($this->sign == '+')
		$this->result = max($this->num1, $this->num2) + min($this->num1, $this->num2);
	else
		$this->result = max($this->num1, $this->num2) - min($this->num1, $this->num2);

	$_SESSION['captchaString'] = max($this->num1, $this->num2) . ' ' . $this->sign . ' ' . min($this->num1, $this->num2) . ' = ';
	$_SESSION['captcha'] = $this->result;

	return $this->getSESSION()['captchaString'];
    }

    public function checkCaptcha($data)
    {
	$data = trim($data);
	if ($data === ''  or !is_numeric($data) or !isset($this->getSESSION()['captcha']))
		return false;
	return ((int)$data === (int)$this->getSESSION()['captcha']);
    }

}