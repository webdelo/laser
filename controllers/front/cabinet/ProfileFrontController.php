<?php
namespace controllers\front\cabinet;
class ProfileFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\controllers\front\traits\CabinetFrontControllerTrait,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'profile',
		'phoneConfirm',
		'languages',

		'ajaxSendEmailCode',
		'ajaxConfirmEmail',
		'ajaxSendPhoneCode',
		'ajaxConfirmPhone',
		'ajaxEditPersonalData',
		'ajaxUploadImage',
		'ajaxChangeImage',
		'ajaxRemoveImage',

		'ajaxSetNotSendBookingMails',
		'ajaxSetNotSendPublicityMails',
		'ajaxEditSurname',
		'ajaxEditName',
		'ajaxEditAdditionalPhone',
		'ajaxEditPhone',
		'ajaxEditLogin',
		
		'ajaxRemoveLanguage',
		'getUserLanguagesBlock',
		'ajaxAddLanguage',
		'ajaxSetMainLanguage',
		'ajaxSetSystemLanguage'
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		try {
			if($this->getController('Authorization')->isGuest())
				return $this->getController('authorization')->authorizationPage();
			elseif( !$this->getREQUEST()->action )
				return $this->defaultAction();
			elseif($this->setAction($name)->isPermissibleAction())
				return $this->callAction($arguments);
			else
				return $this->redirect404();
		} catch (\exceptions\ExceptionAccess $e) {
			$this->redirect404();
		}
	}

	public function defaultAction()
	{
		$this->profile();
	}

	protected function profile()
	{
		$this->confirmProfile();
		
		return $this->setTitle('Профиль: Изменение данных пользователя')
					->setDescription('Изменение контактных данных')
					->setContent('bodyType', 'article')
					->includeTemplate('cabinet/profile/new/profile');
	}
	
	private function confirmProfile() 
	{
		if ( isset( $_GET['code'] ) ) {
			if ( $this->getAuthorizatedUser()->confirmEmail($_GET['code']) ) {
				header('/cabinet/profile/');
			}
		}
	}

	protected function ajaxSendEmailCode()
	{
		$this->ajaxResponse($this->getAuthorizatedUser()->sendEmailCode());
	}

	protected function ajaxConfirmEmail()
	{
		if ( !$this->getAuthorizatedUser()->confirmEmail($this->getPOST()->code) ) {
			$this->setError('code', $this->getErrorsList()['invalidCode'][$this->getLang()])
				 ->ajaxResponse($this->getErrors());
			
			return false;
		}
		$this->ajaxResponse(true);
	}

	protected function languages()
	{
		return $this->setTitle('Языки')
					->setDescription('Языки')
					->setContent('subpage', 'profile/languages')
					->includeTemplate('cabinet/main');
	}

	protected function ajaxSendPhoneCode()
	{
		$this->ajaxResponse($this->getAuthorizatedUser()->sendPhoneCode());
	}

	protected function ajaxConfirmPhone()
	{
		if ( !$this->getAuthorizatedUser()->confirmPhone($this->getPOST()->code) ) {
			$this->setError('code', $this->getErrorsList()['invalidCode'][$this->getLang()])
				 ->ajaxResponse($this->getErrors());
			
			return false;
		}
		$this->ajaxResponse(true);
	}

	protected function ajaxEditPersonalData()
	{
		$client = $this->getObject('\modules\clients\lib\Client', $this->getPOST()->objectId);
		$this->setObject($client);
		$this->ajax($this->modelObject->edit( $this->getPOST(), array('name', 'surname', 'phone', 'mobile', 'notSendBookingMails', 'notSendPublicityMails') ));
	}

	protected function ajaxUploadImage()
	{
		$this->ajaxResponse( $this->getAuthorizatedUser()->uploadImage() );
	}

	protected function ajaxChangeImage()
	{
		$this->ajaxResponse( $this->getAuthorizatedUser()->changeImage() );
	}

	protected function ajaxRemoveImage()
	{
		$this->ajaxResponse($this->getAuthorizatedUser()->removeImage());
	}

	protected function ajaxRemoveLanguage()
	{
		$this->ajaxResponse($this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->getAuthorizatedUser()->id)->removeClientLangByLanguageId($this->getRequest()['languageId']));
	}

	protected function getUserLanguagesBlock()
	{
		$this->includeTemplate('cabinet/profile/userLanguages');
	}

	protected function ajaxAddLanguage()
	{
		$this->ajaxResponse($this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->getAuthorizatedUser()->id)->add($this->getPost()['languageId']));
	}

	protected function ajaxSetMainLanguage()
	{
		$this->ajaxResponse($this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->getAuthorizatedUser()->id)->setMainLang($this->getRequest()['languageId']));
	}

	protected function ajaxSetSystemLanguage()
	{
		$this->ajaxResponse( $this->getAuthorizatedUser()->editField( $this->getPost()['systemLangId'], 'systemLangId' ) );
	}
	
	protected function ajaxSetNotSendPublicityMails()
	{
		$this->ajaxResponse( $this->getAuthorizatedUser()->editField( $this->getPost()['value'], 'notSendPublicityMails' ) );
	}
	
	protected function ajaxSetNotSendBookingMails()
	{
		$this->ajaxResponse( $this->getAuthorizatedUser()->editField( $this->getPost()['value'], 'notSendBookingMails' ) );
	}
	
	protected function ajaxEditName()
	{
		$this->setObject($this->getAuthorizatedUser());
		$this->ajax( $this->getAuthorizatedUser()->editField( $this->getPost()['name'], 'name' ), 'ajax' );
	}
	protected function ajaxEditSurname()
	{
		$this->setObject($this->getAuthorizatedUser());
		$this->ajax( $this->getAuthorizatedUser()->editField( $this->getPost()['surname'], 'surname' ), 'ajax' );
	}
	protected function ajaxEditAdditionalPhone()
	{
		$this->setObject($this->getAuthorizatedUser());
		$this->ajax( $this->getAuthorizatedUser()->editField( $this->getPost()['mobile'], 'mobile' ), 'ajax' );
	}
	
	protected function ajaxEditPhone()
	{
		$this->setObject($this->getAuthorizatedUser());
		if ( !$this->getPOST()['phone'] ) {
			
			return $this->ajaxResponse([ 'phone' => $this->getErrorsList()['empty'][$this->getLang()] ]);
		}
		$this->ajax( $this->getAuthorizatedUser()->editField( $this->getPost()['phone'], 'phone' ), 'ajax' );
	}
}