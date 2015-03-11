<?php
namespace modules\mailers;
class QuickFormFeedback extends \core\mail\MailBase
{
	use \core\traits\validators\Base;

	private $good;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.$_REQUEST['lang'].'/emails/';
		$this->data = $data->getArray();
	}

	public function rules()
	{
		return array(
			'email' => array(
				'validation' => array('_validEmail', array('notEmpty'=>true)),
			),
			'name, phone' => array(
				'validation' => array('_validNotEmpty', array('Имя', 'Телефон')),
			)
		);
	}

	public function sendMail()
	{
		if (!$this->_beforeChange($this->data, array_keys($this->data)))
			return false;
		$res = $this->From($this->noreplyEmail)
				->To($this->adminEmail)
				->Bcc($this->bccEmail)
				->Subject('Быстрый заказ с сайта  '.SEND_FROM)
				->Content('data', $this->data)
				->BodyFromFile('quickOrder.tpl')
				->Send();
		return $res;
	}

	protected function getGood()
	{
		if (empty($this->good))
			$this->good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->data['goodId']);
		return $this->good;
	}
}