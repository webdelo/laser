<?php
namespace controllers\front;
class AuthorizationFrontController extends \controllers\base\AuthorizationBaseController
{
	use	\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\controllers\Meta;

	protected $postLoginKey     = 'login';
	protected $postPasswordKey  = 'password';
	protected $postCookieKey    = 'cookie';
	protected $postCaptchaKey   = 'captcha';
	protected $postSubmitKey    = 'authorization_client_submit';
	protected $requestLogoutKey = 'client_logout';
	protected $allowableGroups  = array(
		'vput.ru'   => array(
			'groups'   => array('modules\clients\lib\Client'),
			'statuses' => array(1),
		)
	);

	protected $sessionArrayKey  = 'clientAuthorizaton';
	protected $cookieArrayKey   = 'clientAuthorizaton';
	protected $permissibleActions = array(
		'ajaxGetAuthorizationBlock',
		'ajaxGetCabinetBlock'
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
			 ->setContent('bodyType', 'authorization')
			 ->includeTemplate('authorization');
	}

	public function ajaxAuthorization()
	{
		parent::logout();
		$_POST[$this->postSubmitKey] = true;
		$this->authorization();
		if ($this->checkUserGroupAndStatus()){
			if( $this->getAuthorizatedUser()->getSystemLang() )
				$this->getObject('\core\i18n\LangHandler')->setLangInSESSION($this->getAuthorizatedUser()->getSystemLang()->getAlias());
			$this->ajaxResponse(1);
		}
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

	public function isClient()
	{
		return get_class($this->authorizatedUser()) == 'modules\clients\lib\Client';
	}

	public function isAdminAuthorizated()
	{
		return isset($_SESSION['adminAuthorizaton']['login']);
	}
}