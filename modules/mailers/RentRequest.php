<?php
namespace modules\mailers;
class RentRequest extends \core\mail\MailBase
{
	use \core\traits\RequestHandler,
		\core\traits\validators\Email;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.\core\i18n\LangHandler::getInstance()->getLang().'/mailers/realties/';
		$this->data = $data;
	}

	public function rules()
	{
		return array(
			'name, phone, cityId, startDate, endDate' => array(
				'validation' => array('_validNotEmpty', array('Имя', 'Телефон', 'Город', 'Дата начала', 'Дата конца')),
			),
			'email' => array(
				'validation' => array('_validEmail'),
			),
		);
	}

	protected function getCity() {
		return new \modules\locations\components\cities\lib\LocationsCity($this->data['cityId']);
	}

	protected function getNights()
	{
		return \core\utils\Dates::nightsInPeriod($this->data['startDate'], $this->data['endDate']);
	}

	public function sendMailToManagers()
	{

		if (!$this->_beforeChange($this->data, array_keys($this->data)))
			return false;

		$managers = array();
		$activeManagers = (new \modules\administrators\lib\Administrators)->getActiveManagers();
		$activeManagers = $activeManagers->count() > 0 ? $activeManagers : new \modules\administrators\lib\Administrators;
		foreach($activeManagers as $manager) {
			if( \core\utils\Utils::isEmail($manager->getUserData()['email']) )
				$managers[] = $manager->getUserData()['email'];
		}

		return $this->From($this->noreplyEmail)
					->To($managers)
					->Subject('Заявка на подбор жилья с сайта '.SEND_FROM)
					->Content('data', new \core\ArrayWrapper($this->data))
					->BodyFromFile('rentRequest.tpl')
					->Send();
	}

}