<?php
class MailShopcart extends MailBase
{
	use \core\traits\RequestHandler;
	
	private $shopcart;
	private $serviceTypes = array(
						'pohorony'=>'Заказать похороны',
						'cremacia'=>'Заказать кремацию',
						'gruz200'=>'Заказать перевозку Груза 200',
					);

	public function rules()
	{
		return array(
			'clientName' => array(
				'validation' => array('_validNotEmpty', array(' ', '  ', '   ', '    ', '    ')),
			),
			'phone' => array(
				'validation' => array('_validNotEmpty', array(' ', '  ', '   ', '    ', '    ')),
			),
			'email' => array(
				'validation' => array('_validEmail', array('notEmpty'=>true)),
			),
		);
	}

	public function __construct($shopcart)
	{
		parent::__construct();
		$this->templates = TEMPLATES.UrlDecoder::getInstance()->getDomainAlias().'/'.$this->getREQUEST()['lang'].'/emails/';
		$this->data = $this->getPOST();
		$this->shopcart = $shopcart;
	}

	public function validateClientData($data)
	{
		return $this->_beforeChange($data, array_keys($data));
	}

	public function MailShopcartToAdmin()
	{
		$res = $this->From($this->noreplyEmail)
				->To($this->adminEmail)
				->Subject('Новый заказ с сайта  '.SEND_FROM)
				->Content('data', $this->data)
				->Content('shopcart', $this->shopcart)
				->Content('serviceType', $this->setServiceType())
				->BodyFromFile('shopcartMailToAdmin.tpl')
				->Send();
		if($res)
			return $this->resetMail();
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function MailShopcartToClient()
	{
		$res = $this->From($this->noreplyEmail)
				->To($this->data['email'])
				->Subject('Заказ с сайта  '.SEND_FROM)
				->Content('data', $this->data)
				->Content('shopcart', $this->shopcart)
				->Content('serviceType', $this->setServiceType())
				->BodyFromFile('shopcartMailToClient.tpl')
				->Send();
		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	private function setServiceType()
	{
		$typeAlias = Controller::__get('CalculatorController')->getCalculatorType();
		return $this->serviceTypes[$typeAlias];
	}

}
