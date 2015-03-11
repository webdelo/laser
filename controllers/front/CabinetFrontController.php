<?php
namespace controllers\front;
class CabinetFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\controllers\Breadcrumbs;

	protected $permissibleActions = array(
		'current',
		'archive',
		'order',
		'orders',
		'personal',
		'password',
		'ajaxGetPersonalDataBlockContent',
		'ajaxEditPassword'
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{	
		if($this->getController('Authorization')->isGuest())
			return $this->getController('Authorization')->authorizationPage();
		else
			parent::__call($name, $arguments);
	}

	protected function defaultAction()
	{
		$this->cabinetMainPage();
	}

	protected function cabinetMainPage()
	{
		$this->setTitle('Личный кабинет')
			 ->setDescription('Личный кабинет')
			 ->setKeywords('Личный кабинет')
			 ->setLevel('Личный кабинет')
			 ->setContent('client', $this->getAuthorizatedUser())
			 ->includeTemplate('cabinet/cabinet');
	}
	
	protected function orders()
	{
		$this->setTitle('Личный кабинет - Активные заказы')
			 ->setDescription('Личный кабинет - Активные заказы')
			 ->setKeywords('Личный кабинет - Активные заказы')
			 ->setLevel('Личный кабинет')
			// ->setContent('content', $this->getShopcartGoodsTableContent())
			 ->includeTemplate('cabinet/orders');
	}

	protected function archive()
	{
		$this->setTitle('Личный кабинет - Архив')
			 ->setDescription('Личный кабинет - Архив')
			 ->setKeywords('Личный кабинет - Архив')
			 ->setLevel('Личный кабинет')
			// ->setContent('content', $this->getShopcartGoodsTableContent())
			 ->includeTemplate('cabinet/archive');
	}

	protected function order()
	{
		$this->setTitle('Личный кабинет - Просмотр ордера')
			 ->setDescription('Личный кабинет - Просмотр ордера')
			 ->setKeywords('Личный кабинет - Просмотр ордера')
			 ->setLevel('Личный кабинет')
			// ->setContent('content', $this->getShopcartGoodsTableContent())
			 ->includeTemplate('cabinet/order');
	}

	protected function personal()
	{
		$this->setTitle('Личный кабинет - Личные данные')
			 ->setDescription('Личный кабинет - Личные данные')
			 ->setKeywords('Личный кабинет - Личные данные')
			 ->setLevel('Личный кабинет')
			 ->setContent('client', $this->getAuthorizatedUser())
			 ->includeTemplate('cabinet/personal');
	}
	
	protected function password()
	{
		$this->setTitle('Личный кабинет - Изменение пароля')
			 ->setDescription('Личный кабинет - Изменение пароля')
			 ->setKeywords('Личный кабинет - Изменение пароля')
			 ->setLevel('Личный кабинет')
			// ->setContent('content', $this->getShopcartGoodsTableContent())
			 ->includeTemplate('cabinet/password');
	}
	
	private function reAuthorization()
	{
		//$authorization = $this->getController('authorization');
		//$authorization->authorizeUser($this->getAuthorizatedUser(), $authorization->isUserAuthorizedInCookie());
		//return $this;
	}

	protected function ajaxEditPassword()
	{
		if ($this->getAuthorizatedUser()->checkPassword($this->getPOST()['password'])){
			$result = $this->getAuthorizatedUser()->editPassword($this->getPOST()['newPassword'], $this->getPOST()['passwordConfirm']);
			$this->setObject($this->getAuthorizatedUser())
				 ->ajax($result);
			if ($result)
				$this->reAuthorization();
		} else {
			$this->setError('password')->ajaxResponse($this->getErrors());
		}
	}
	
	private function getContent($action)
	{
		ob_start();
		$this->includeTemplate('cabinet/'.$action);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	private function validateOrderUrl()
	{
		if( isset($_REQUEST[1]))
			return false;
		if( ! isset($_REQUEST[0]))
			return false;
		if($this->action != 'current'   &&   $this->action != 'archive')
			return false;
		return $this->isOrderBelongsToClientByNr($_REQUEST[0]);
	}

	private function isOrderBelongsToClientByNr($nr)
	{
		$order = $this->getController('Order')->getOrderByNr($nr);
		return $order  ?  $order->clientId == $this->getAuthorizatedUser()->id  :  false;
	}

	protected function ajaxGetPersonalDataBlockContent($clientType = null)
	{
		return $this->setContent('client', $this->getAuthorizatedUser())
				->includeTemplate('cabinet/personalData'.ucfirst(isset($clientType) ? $clientType : $this->getPOST()['clientType']));
	}

}