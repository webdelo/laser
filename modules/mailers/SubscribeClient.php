<?php
namespace modules\mailers;
class SubscribeClient extends \core\mail\MailBase
{
	use \core\traits\RequestHandler,
		\core\traits\validators\Email;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.\core\i18n\LangHandler::getInstance()->getLang().'/emails/';
		$this->data = $data->getArray();
	}

	public function rules()
	{
		return array(
			'clientEmail' => array(
				'validation' => array('_validEmail', array('notEmpty'=>true, 'key'=>'clientEmail')),
			)
		);
	}

	public function sendMailToManagers()
	{
		if (!$this->_beforeChange($this->data, array_keys($this->data)))
			return false;

		$managers = array();
		$activeManagers = (new \modules\administrators\lib\Administrators)->getActiveManagers();
		$activeManagers = $activeManagers->count() ? $activeManagers : new \modules\administrators\lib\Administrators;
		foreach($activeManagers as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email']) )
				$managers[] = $manager->getUserData()['email'];

		return $this->From($this->noreplyEmail)
					->To($managers)
					->Subject('Оформлена новая подписка на сайте '.SEND_FROM)
					->Content('data', new \core\ArrayWrapper($this->data))
					->BodyFromFile('subscription.tpl')
					->Send();
	}

}