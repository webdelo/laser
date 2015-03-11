<?php
namespace controllers\front;
class AuthorizationFrontController extends \controllers\base\AuthorizationBaseController
{
	use	\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\controllers\Meta,
		\core\traits\controllers\Breadcrumbs;

	protected $postLoginKey     = 'login';
	protected $postPasswordKey  = 'password';
	protected $postCookieKey    = 'cookie';
	protected $postCaptchaKey   = 'captcha';
	protected $postSubmitKey    = 'authorization_client_submit';
	protected $requestLogoutKey = 'client_logout';
	protected $allowableGroups  = array(
		'run-laser.com'   => array(
			'groups'   => array('modules\clients\lib\Client'),
			'statuses' => array(1),
		)
	);

	protected $sessionArrayKey  = 'clientAuthorizaton';
	protected $cookieArrayKey   = 'clientAuthorizaton';
	protected $permissibleActions = array(
		'ajaxGetAuthorizationBlock',
		'ajaxGetCabinetBlock',
	);

	public function __construct()
	{
		parent::__construct();
		$this->authorization();
	}

	public function __call($name, $arg)
	{
		parent::__call($name, $arg);
	}

	protected function redirect404()
	{
	    echo '404';
	}

	public function authorizationPage()
	{
		$this->setTitle('Авторизация')
			 ->setDescription('Авторизация')
			 ->setKeywords('Авторизация')
			 ->setLevel('Авторизация')
			 ->includeTemplate('authorization');
	}
	
	protected function ajaxGetAuthorizationBlock()
	{
		if($this->checkUserGroupAndStatus())
			echo 'NaN';
		else
			echo $this->getAuthorizationBlock();
	}

	private function getAuthorizationBlock()
	{
	    ob_start();
	    $this->includeTemplate('headerAutorizationBlock');
	    $contents = ob_get_contents();
	    ob_end_clean();
	    return $contents;
	}

	public function ajaxAuthorization()
	{
		parent::logout();
		$_POST[$this->postSubmitKey] = true;
		$this->authorization();
		if ($this->checkNeedDeleteGuestShopcart())
			$this->getController('Shopcart')->ajaxDelAuthorizatedShopcartSaveGuestShopcart();
		if ($this->checkUserGroupAndStatus())
				$this->ajaxResponse(1);
		else
			$this->ajaxResponse($this->getErrorArray());
		return $this;
	}

	private function checkUserGroupAndStatus()
	{
		if (!($this->authorizatedUser() instanceof \core\authorization\Guest)) {
		    $user = $this->authorizatedUser();
			if ( in_array(get_class($user), $this->allowableGroups[$this->getCurrentDomainAlias()]['groups']) ){
				if ( in_array($user->statusId, $this->allowableGroups[$this->getCurrentDomainAlias()]['statuses']) )
					return true;
				else {
					$this->errorMessage = 'This user was blocked!';
					$this->errorCode    = 2048;
				}
			} else {
				$this->errorMessage = 'Access denied for this user!';
				$this->errorCode    = 1024;
			}
			parent::logout();
		}
		return false;
	}
	
	private function checkNeedDeleteGuestShopcart()
	{
		if (isset($_POST['del_auth_shopcart']) && ($_POST['del_auth_shopcart'] == '1'))
			return true;
	}

	public function ajaxCheckAuthorization()
	{
		$this->ajaxResponse($this->checkUserGroupAndStatus());
		return true;
	}

	public function logout()
	{
		parent::logout();
		$this->ajaxResponse(true);
	}

	public function ajaxGetLogin()
	{
		$this->ajaxResponse($this->authorizatedUser()->getLogin());
		return true;
	}

	public function ajaxGetGroup()
	{
		$this->ajaxResponse($this->authorizatedUser()->getGroup());
		return true;
	}

	public function ajaxGetCabinetController()
	{
		$this->ajaxResponse($this->getCabinetController());
		return true;
	}

	public function getCabinetController () {
		return 'cabinet';
	}

	public function getHeaderAuthorizationBlock()
	{
		ob_start();
		$this->includeTemplate('headerAuthorizationBlock');
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function isClient()
	{
		return get_class($this->authorizatedUser()) == 'Client';
	}

	public function ajaxGetHeaderAuthorizationBlock()
	{
		echo $this->getHeaderAuthorizationBlock();
	}

	protected function ajaxGetCabinetBlock()
	{
		echo $this->getCabinetBlock();
	}

	private function getCabinetBlock()
	{
		ob_start();
		$this->includeTemplate('headerCabinetBlock');
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function isAdminAuthorizated()
	{
		return isset($_SESSION['adminAuthorizaton']['login']);
	}
}