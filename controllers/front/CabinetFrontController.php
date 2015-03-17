<?php
namespace controllers\front;
class CabinetFrontController extends \controllers\base\SubControllersBaseController
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\RequestHandler,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\core\traits\controllers\Authorization,
		\core\traits\controllers\Breadcrumbs,
		\controllers\front\traits\CabinetFrontControllerTrait;

	protected $permissibleActions = array(
		'authorization',

		/* Start: ajax authorization page methods */
		'ajaxEditLogin',
		'ajaxEditPassword',
		/*   End: ajax authorization page methods */

		/* Start: ajax tools page methods */
		'ajaxToolsFormSend',
		/*   End: ajax authorization page methods */
	);

	public function __construct()
	{
		parent::__construct();
	}

	protected function defaultAction()
	{
		$this->chooseController('realties');
	}

	protected function authorization()
	{
		if($this->getController('Authorization')->isGuest())
			return $this->getController('authorization')->authorizationPage();

		return $this->setTitle('Изменение данных авторизации')
					->setDescription('Изменение данных авторизации Вашего аккаунта')
					->setContent('subpage', 'authorization')
					->includeTemplate('cabinet/main');
	}

	protected function ajaxEditLogin()
	{
		if ($this->getAuthorizatedUser()->checkPassword($this->getPOST()['password'])){
			$result = $this->getAuthorizatedUser()->editLogin($this->getPOST()['login']);
			$this->setObject($this->getAuthorizatedUser())
				 ->ajax($result);
			if ($result) {
				$this->getAuthorizatedUser()->deconfirmEmail();
				// reauthorization
				$this->reAuthorization();
			}
		} else {
			$this->setError('password')->ajaxResponse($this->getErrors());
		}
	}

	private function reAuthorization()
	{
		$authorization = $this->getController('authorization');
		$authorization->authorizeUser($this->getAuthorizatedUser(), $authorization->isUserAuthorizedInCookie());
		return $this;
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

	public function ajaxToolsFormSend()
	{
		$toolsForm = new \modules\mailers\ToolsForm($this->getPOST());
		$this->setObject($toolsForm)->ajax( $this->modelObject->sendMail(), 'ajax', true );
	}

}
