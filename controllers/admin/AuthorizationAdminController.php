<?php
namespace controllers\admin;
class AuthorizationAdminController extends \controllers\base\AuthorizationBaseController
{
	protected $postLoginKey     = 'login';
	protected $postPasswordKey  = 'password';
	protected $postCookieKey    = 'cookie';
	protected $postCaptchaKey   = 'captcha';
	protected $postSubmitKey    = 'authorization_submit';
	protected $requestLogoutKey = 'logout';

	protected $sessionArrayKey = 'adminAuthorizaton';
	protected $cookieArrayKey  = 'adminAuthorizaton';
	private $administrators = array(
		'modules\administrators\lib\Administrator',
		'modules\managers\lib\Manager'
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function checkAuthorization()
	{
		$this->authorization();
		if ($this->checkUserGroup())
			return true;
		$this->viewTemplate('authorization');
		return false;
	}

	public function checkUserGroup()
	{
		if ($this->authorizatedUser() instanceof \core\authorization\Guest)
			return false;
		if (in_array(get_class($this->authorizatedUser()), $this->administrators)){
			return true;
		} else {
			$this->errorMessage = 'Access denied for this user!';
		}
	}

	private function viewTemplate($tpl)
	{
		include(TEMPLATES_ADMIN.$tpl.'.tpl');
	}

	public function logout()
	{
		parent::logout();
		header('Location: '.$this->getSERVER()['HTTP_REFERER']);
	}

}