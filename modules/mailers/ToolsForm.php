<?php
namespace modules\mailers;
class ToolsForm extends \core\mail\MailBase
{
	use \core\traits\validators\Base,
		\core\traits\validators\Captcha;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.\core\i18n\LangHandler::getInstance()->getLang().'/toolsEmail/';
		$this->data = $data->getArray();
	}

	public function rules()
	{
		return array(
			'theme, message' => array(
				'validation' => array('_validNotEmpty', array('Ф.И.О.', 'Сообщение')),
			)
		);
	}

	public function sendMail()
	{
		if (!$this->_beforeChange($this->data, array_keys($this->data)))
			return false;

		$managers = array();
		$activeManagers = (new \modules\administrators\lib\Administrators)->getActiveManagers();
		$activeManagers = $activeManagers->count() ? $activeManagers : new \modules\administrators\lib\Administrators;
		foreach($activeManagers as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email']) )
				$managers[] = $manager->getUserData()['email'];

		$res = $this->From($this->noreplyEmail)
				->To($managers)
				->Bcc($this->bccEmail)
				->Subject(' Внимание! Пришло новое сообщение из кабинета сайта '.SEND_FROM)
				->Content('data', new \core\ArrayWrapper($this->data))
				->BodyFromFile('toolsMessage.tpl')
				->Send();

		return $res;
	}
}
