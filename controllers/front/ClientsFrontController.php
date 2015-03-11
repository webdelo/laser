<?php
namespace controllers\front;
class ClientsFrontController extends \controllers\base\ClientsBaseController
{
	use	\core\traits\controllers\ControllersHandler;

	protected $permissibleActions = array(
		'ajaxAdd',
		'ajaxChangeLogin',
		'ajaxChangePassword',
		'ajaxSavePersonalData',
		'ajaxEditDeliveryAddress'
	);

	protected $rules = array(
		'deliveryInformationNotEmpty'=>array(
			'deliveryCountry' => array(
				'validation' => array('_validNotEmpty'),
			),
			'deliveryCity' => array(
				'validation' => array('_validNotEmpty', array('Город')),
			),
			'deliveryStreet' => array(
				'validation' => array('_validNotEmpty', array('Улица')),
			),
			'deliveryHome' => array(
				'validation' => array('_validNotEmpty', array('Дом')),
			),
			'deliveryPerson' => array(
				'validation' => array('_validNotEmpty', array('Контактное лицо')),
			),
		),

		'payerInformationNotEmpty'=>array(
			'name' => array(
				'validation' => array('_validNotEmpty', array('Имя')),
			),
			'surname' => array(
				'validation' => array('_validNotEmpty', array('Фамилия')),
			),
			'patronimic' => array(
				'validation' => array('_validNotEmpty', array('Отчество')),
			),
		),
	);

	protected $fields = array(
		'deliveryFields'=>array(
			'deliveryCountry',
			'deliveryIndex',
			'deliveryRegion',
			'deliveryCity',
			'deliveryStreet' ,
			'deliveryHome',
			'deliveryBlock',
			'deliveryFlat',
			'deliveryPerson',
		),
		'payerFields'=>array(
			'name',
			'patronimic',
			'surname',
			'phone',
			'mobile',
			'birthday',
			'birthDate',
			'birthMonth',
			'birthYear',
			'addEmails'
		),
	);

	public function  __construct()
	{
		parent::__construct();
	}

	public function NormalizeClientAddress($Data)
	{
		$NewData = $Data;
		
		if (mb_substr($NewData['region'], 0, 3,'UTF-8') == "р. ") { $NewData['region'] = substr($NewData['region'], 4);
		} elseif (mb_substr($NewData['region'], 0, 2,'UTF-8') == "р.") { $NewData['region'] = substr($NewData['region'], 3);}
		
		if (mb_substr($NewData['city'], 0, 3,'UTF-8') == "г. ") { $NewData['city'] = substr($NewData['city'], 4);
		} elseif (mb_substr($NewData['city'], 0, 2,'UTF-8') == "г.") { $NewData['city'] = substr($NewData['city'], 3);}
				
		if (mb_substr($NewData['street'], 0, 4,'UTF-8') == "ул. ") { $NewData['street'] = substr($NewData['street'], 6);
		} elseif (mb_substr($NewData['street'], 0, 3,'UTF-8') == "ул.") { $NewData['street'] = substr($NewData['street'], 5);}
				
		if (mb_substr($NewData['home'], 0, 3,'UTF-8') == "д. ") { $NewData['home'] = substr($NewData['home'], 4);
		} elseif (mb_substr($NewData['home'], 0, 2,'UTF-8') == "д.") { $NewData['home'] = substr($NewData['home'], 3);}
				
		if (mb_substr($NewData['block'], 0, 6,'UTF-8') == "корп. ") { $NewData['block'] = substr($NewData['block'], 10);
		} elseif (mb_substr($NewData['block'], 0, 5,'UTF-8') == "корп.") { $NewData['block'] = substr($NewData['block'], 9);}
				
		if (mb_substr($NewData['flat'], 0, 4,'UTF-8') == "кв. ") { $NewData['flat'] = substr($NewData['flat'], 6);
		} elseif (mb_substr($NewData['flat'], 0, 3,'UTF-8') == "кв.") { $NewData['flat'] = substr($NewData['flat'], 5);}
				
		return $NewData;
	}
	
	protected function ajaxAdd()
	{
		if(empty($_POST['login']))
			$this->setObject($this->_config->getObjectsClass())->modelObject->setError('login', 'Укажите ваш e-mail');

		$_POST['country'] = 'Россия';
		$_POST['name'] = 'регистрация через сайт';
		$_POST['phone'] = 'регистрация через сайт';
		$_POST['statusId'] = 1;
		$_POST['surname'] = 'регистрация через сайт';
		$_POST['city'] = 'регистрация через сайт';
		$_POST['home'] = 'регистрация через сайт';
		$_POST['street'] = 'регистрация через сайт';

		$result = parent::add();

		$this->getController('Authorization')->authorization();
		//$this->getController('Shopcart')->ajaxDelAuthorizatedShopcartSaveGuestShopcart();
		echo $result;
	}

	protected function ajaxChangeLogin()
	{
		$loginIsValid = $this->validateLogin();
		$passwordIsValid = $this->validatePassword();
		if( $loginIsValid   &&    $passwordIsValid)
			echo $this->changeLoginAction();
		else
			$this->ajaxResponse( $this->getErrors() );
	}

	private function changeLoginAction()
	{
		$this->getPOST()['id'] = $this->getAuthorizatedUser()->id;
		$result = parent::editLogin();
		if($result){
			$this->getPOST()['authorization_client_submit'] = 1;
			$this->getController('Authorization')->authorization();
			return 1;
		}
		else
			return $result;
	}

	private function validateLogin()
	{
		if( $this->getPOST()['login'] == $this->getAuthorizatedUser()->getLogin() ){
			$this->setError('login', 'Укажите новый e-mail');
			return false;
		}

		if( $this->_config->validLogin( $this->getPOST()['login'], true ) )
			return true;

		$this->setError('login', 'Введите правильный e-mail');
		return false;
	}

	private function validatePassword()
	{
		if( empty($this->getPOST()['password']) ){
			$this->setError('password', 'Укажите пароль');
			return false;
		}
		if( $this->getAuthorizatedUser()->getPassword() != md5($this->getPOST()['password']) ){
			$this->setError('password', 'Неверный пароль');
			return false;
		}
		return true;
	}

	public function ajaxChangePassword()
	{
		$passwordIsValid = $this->validatePassword();
		$newPasswordIsValid = $this->newPasswordIsValid();
		if( $passwordIsValid   &&    $newPasswordIsValid){
			$this->getPOST()['id'] = $this->getAuthorizatedUser()->id;
			$this->getPOST()['password'] = $this->getPOST()['newPassword'];
			$this->editPassword();
			$this->getPOST()['login'] = $this->getAuthorizatedUser()->getLogin();
			$this->getPOST()['authorization_client_submit'] = 1;
			$this->getController('Authorization')->authorization();
			echo 1;
		}
		else
			$this->ajaxResponse( $this->getErrors() );
	}

	private function newPasswordIsValid()
	{
		if(empty($this->getPOST()['newPassword'])){
			$this->setError('newPassword', 'Укажите новый пароль');
			return false;
		}

		if( $this->getPOST()['newPassword']==$this->getPOST()['newPasswordConfirm'] )
			return true;

		$this->setError('newPassword', 'Пароли не совпадают');
		$this->setError('newPasswordConfirm', 'Пароли не совпадают');
		return false;
	}

	protected function ajaxSavePersonalData()
	{
		$this->getPOST()['id'] = $this->getAuthorizatedUser()->id;
		$this->getPOST()['login'] = $this->getAuthorizatedUser()->getLogin();
		$this->getPOST()['password'] = $this->getAuthorizatedUser()->getPassword();

		$this->setClientObject($this->getPOST()['clientType']);
		$rules = $this->getPost()['notEmptyRules'] ? $this->rules[$this->getPost()['notEmptyRules']] : null;
		$fields = $this->getPost()['fields'] ? $this->fields[$this->getPost()['fields']] : null;

		return parent::editAction($fields, $rules);
	}

	protected function ajaxEditDeliveryAddress()
	{
		$client = $this->getObject($this->objectClass, $this->getAuthorizatedUser()->id);
		$NewData = $this->NormalizeClientAddress($this->getPOST());
		$this->setObject($client)->ajax($this->modelObject->edit($NewData), 'ajax', true);
	}	

	private function setClientObject($clientType)
	{
		$this->setObject( $this->getClientObjectByTypeAndId($clientType, $this->getPOST()['id']) );
	}

	private function getClientObjectByTypeAndId($clientType, $clientId)
	{
		return new \modules\clients\lib\ClientObject((int)$clientId, $this->getConfigClassNameByClientType($clientType));
	}

	private function getConfigClassNameByClientType($clientType)
	{
		return '\modules\clients\lib\Client' . ucfirst($clientType) . 'Config';
	}

}
