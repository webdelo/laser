<?php
namespace modules\mailers;
class ModalMessages extends \core\mail\MailBase
{
	use \core\traits\RequestHandler,
		\core\traits\validators\Email;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = TEMPLATES.\core\url\UrlDecoder::getInstance()->getDomainAlias().'/'.\core\i18n\LangHandler::getInstance()->getLang().'/mailers/modals/';
		$this->data = $data;
	}

	public function sendMailToManagers()
	{
		$managers = array();
		$activeManagers = (new \modules\administrators\lib\Administrators)->getActiveManagers();
		$activeManagers = $activeManagers->count() ? $activeManagers : new \modules\administrators\lib\Administrators;
		foreach($activeManagers as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email']) )
				$managers[] = $manager->getUserData()['email'];

		if ( empty($this->data['name']) ) {
			$this->setError('name', 'Введите пожалуйста свое имя');
			$errors = true;
		}

		if ( empty($this->data['message']) ) {
			$this->setError('message', 'Напишите пожалуйста сообщение');
			$errors = true;
		}

		if ( empty($this->data['email']) ) {
			$this->setError('email', 'Введите пожалуйста свой e-mail');
			$errors = true;
		}

		if ( !\core\utils\Utils::isEmail($this->data['email']) ) {
			$this->setError('email', 'Поле e-mail заполнено не верно');
			$errors = true;
		}

		if ( isset($errors) ) {
			return false;
		}

		return $this->From($this->noreplyEmail)
					->To($managers)
					->Subject('Клиент отправил сообщение с сайта '.SEND_FROM)
					->Content('data', $this->data)
					->BodyFromFile('messageToManagers.tpl')
					->Send();
	}

	public function sendAddRealtyMessage()
	{
		$managers = array();
		$admins = new \modules\administrators\lib\Administrators;
		foreach($admins->getActiveManagers() as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email']) )
				$managers[] = $manager->getUserData()['email'];

		if ( empty($this->data['name']) ) {
			$this->setError('name', 'Введите пожалуйста свое имя');
			$errors = true;
		}

		if ( empty($this->data['message']) ) {
			$this->setError('message', 'Напишите пожалуйста сообщение');
			$errors = true;
		}

		if ( empty($this->data['phone']) ) {
			$this->setError('phone', 'Введите пожалуйста свой номер телефона');
			$errors = true;
		}

		if ( empty($this->data['email']) ) {
			$this->setError('email', 'Введите пожалуйста свой e-mail');
			$errors = true;
		}

		if ( !\core\utils\Utils::isEmail($this->data['email']) ) {
			$this->setError('email', 'Поле e-mail заполнено не верно');
			$errors = true;
		}

		if ( isset($errors) ) {
			return false;
		}

		return $this->From($this->noreplyEmail)
					->To($managers)
					->Subject('Заявка на добавление нового объекта недвижимости на сайте '.SEND_FROM)
					->Content('data', $this->data)
					->BodyFromFile('addRealty.tpl')
					->Send();
	}

	public function sendBookingMessage()
	{
		if ( empty($this->data['name']) ) {
			$this->setError('name', 'Введите пожалуйста свое имя');
			$errors = true;
		}

		if ( empty($this->data['startDate']) ) {
			$this->setError('name', 'Укажите дату въезда');
			$errors = true;
		}

		if ( empty($this->data['endDate']) ) {
			$this->setError('name', 'Укажите дату выезда');
			$errors = true;
		}

		if ( empty($this->data['message']) ) {
			$this->setError('message', 'Напишите пожалуйста сообщение');
			$errors = true;
		}

		if ( empty($this->data['phone']) ) {
			$this->setError('phone', 'Введите пожалуйста свой номер телефона');
			$errors = true;
		}

		if ( empty($this->data['email']) ) {
			$this->setError('email', 'Введите пожалуйста свой e-mail');
			$errors = true;
		}

		if ( !\core\utils\Utils::isEmail($this->data['email']) ) {
			$this->setError('email', 'Поле e-mail заполнено не верно');
			$errors = true;
		}

		if ( isset($errors) ) {
			return false;
		}

		$activeManagers = (new \modules\administrators\lib\Administrators)->getActiveManagers();
		$activeManagers = $activeManagers->count() ? $activeManagers : new \modules\administrators\lib\Administrators;
		foreach($activeManagers as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email']) )
				$managers[] = $manager->getUserData()['email'];

		$realty = new \modules\realties\lib\Realty($this->data->objectId);

		return $this->From($this->noreplyEmail)
					->To($managers)
					->Subject('Заказ аренды недвижимости на сайте '.SEND_FROM)
					->Content('data', $this->data)
					->Content('object', $realty)
					->Content('period', new \modules\bookingPeriod\lib\BookingPeriod())
					->BodyFromFile('booking.tpl')
					->Send();
	}

}