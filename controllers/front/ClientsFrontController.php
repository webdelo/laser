<?php
namespace controllers\front;
class ClientsFrontController extends \controllers\base\ClientsBaseController
{
	use	\core\traits\controllers\ControllersHandler;

	protected $permissibleActions = array(
		'ajaxAdd',
	);

	public function  __construct()
	{
		parent::__construct();
	}

	protected function ajaxAdd()
	{
		$clientRegistrator = new \modules\clients\lib\ClientRegistrator();
		$clientId = $clientRegistrator->registration($this->getPOST()['login'], $this->getPOST()['password'], $this->getPOST()['passwordConfirm'], $frontRegistration = true);
		
		if ($clientId) {
			$clientRegistrator->authorize($this->getPOST()['cookie']);
			$clientRegistrator->setSubscribed($this->getPOST()['newsSubscribed']);
		}
		$this->setObject($clientRegistrator)->ajax($clientId);
	}
}
