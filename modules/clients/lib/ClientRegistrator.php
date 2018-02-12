<?php
namespace modules\clients\lib;
class ClientRegistrator
{
	use \core\traits\controllers\Authorization,
		\core\traits\ObjectPool,
		\core\traits\RequestHandler;

	private $client;
	private $_clients;

	public function __construct()
	{
		$this->instanseClients();
	}

	private function instanseClients()
	{
		$this->_clients = new Clients;
		return $this;
	}

	private function getClients()
	{
		return $this->_clients;
	}

	public function registration($login, $password, $passwordConfirm, $frontRegistration = false)
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		$systemLangId = $this->getObject('\modules\languages\lib\Languages')
							 ->getObjectByAlias($this->getCurrentLang())->id;

		$data = array(
			'login'    => $login,
			'password' => $password,
			'statusId' => ClientConfig::STATUS_ACTIVE,
			'regType'  => (bool)$frontRegistration,
			'ip'       => $ip,

			'notSendBookingMails'   => 1,
			'notSendPublicityMails' => 1,

			'systemLangId' => $systemLangId,
		);

		$objectId = $this->getClients()->setLogin($login)
									   ->setPassword($password, $passwordConfirm)
									   ->add($data, array_keys($data));
		if ($objectId)
			$this->client = $this->getClients()->getObjectById($objectId);
		return $objectId;
	}

	public function getErrors()
	{
		return $this->getClients()->getErrors();
	}

	public function authorize($cookie = false)
	{
		return $this->getController('Authorization')->authorizeUser($this->client, $cookie);
	}

	public function setSubscribed($newsSubscribed)
	{
		return $this->client->editField($newsSubscribed, 'newsSubscribed');
	}
}