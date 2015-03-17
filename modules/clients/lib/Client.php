<?php
namespace modules\clients\lib;
class Client extends \core\authorization\AuthorizatedUser implements \interfaces\IUserForAuthorization
{
	use \core\traits\ObjectPool,
		\core\modules\statuses\StatusTraitDecorator,
		\core\modules\images\ImagesTraitDecorator,
		\core\modules\rights\RightsListTraitDecorator;

	private $_bookings;
	private $_trips;
	private $_realties;

    protected $configClass = '\modules\clients\lib\ClientConfig';

	public function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function getAvatar($size = null)
	{
		return ( $this->getFirstPrimaryImage() instanceof \core\modules\images\ImageNoop )
			? '/images/content/usersNoimage/112x112.png'
			: $this->getFirstPrimaryImage()->getFocusImage($size) ;
	}

	public function getRealties()
	{
		if (empty($this->_realties)){
			$this->_realties =  new \modules\realties\lib\Realties();
			$this->_realties->setSubquery(' AND `clientId` = ?d ', $this->id);
		}
		return $this->_realties;
	}

	public function getBookings()
	{
		if (empty($this->_bookings)){
			$this->_bookings =  new \modules\bookings\lib\Bookings();
			$realties = new \modules\realties\lib\Realties;
			$this->_bookings->setSubquery(' AND `realtyId` IN ( SELECT `id` FROM `'.$realties->mainTable().'` WHERE `clientId` = ?d ) ', $this->id);
		}
		return $this->_bookings;
	}

	public function getTrips()
	{
		if (empty($this->_trips)){
			$this->_trips =  new \modules\bookings\lib\Bookings();
			$realties = new \modules\realties\lib\Realties;
			$this->_trips->setSubquery(' AND `clientId` = ?d  ', $this->id);
		}
		return $this->_trips;
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	public function getAllName()
	{
		return $this->surname.' '.$this->name.' '.$this->patronimic;
	}

	public function getShortName()
	{
		if ( $this->issetFullName() ) {
			return $this->getShortNameByFullName();
		}
		if ( $this->issetName() ) {
			return $this->getShortNameByName();
		}
		if ( $this->issetSurname() ) {
			return $this->getShortNameBySurname();
		}

		return $this->getLogin();
	}

	protected function getShortNameByFullName()
	{
		$name = '';
		$name .= ucfirst($this->surname);
		$name .= ' '. mb_substr(ucfirst($this->name), 0, 1, 'utf-8').'.';
		$name .= ' '. mb_substr(ucfirst($this->patronimic), 0, 1, 'utf-8').'.';
		return $name;
	}

	protected function getShortNameByName()
	{
		$name = '';
		$name .= ucfirst($this->surname);
		$name .= ' '. mb_substr(ucfirst($this->name), 0, 1, 'utf-8').'.';
		return $name;
	}

	protected function getShortNameBySurname()
	{
		$name = '';
		$name .= ucfirst($this->surname);
		return $name;
	}


	private function issetFullName()
	{
		return ($this->surname && $this->name && $this->patronimic);
	}

	private function issetName()
	{
		return !!$this->name;
	}

	private function issetSurname()
	{
		return !!$this->surname;
	}

	public function getName()
	{
		return trim($this->name) ? $this->name : 'No name';
	}

	public function getFullName()
	{
		return trim($this->getAllName()) ? $this->getAllName() : 'Имя не указано';
	}

	public function getDefaultName()
	{
		return $this->getName();
	}

	public function getMobile()
	{
		return $this->phone ? $this->phone : $this->mobile ;
	}

	public function getPath()
	{
		return '/profile/'.$this->id.'/';
	}

	public function uploadImage()
	{
		$imagesFilesUploader = new \core\modules\images\ImagesFileUploader();
		$resultUpload = $imagesFilesUploader->upload();
		$data = array(
			'tmpName'    => $resultUpload['message'],
			'title'      => $resultUpload['basename'],
			'name'       => $resultUpload['basename'],
			'statusId'   => 1,
			'categoryId' => 2,
			'date'       => null
		);

		return $this->getImages()->add($data);
	}

	public function changeImage()
	{
		if ( $this->isNotNoop($this->getFirstPrimaryImage()) ) {
			$this->removeImage();
		}
		return $this->uploadImage();
	}

	public function removeImage()
	{
		return $this->getFirstPrimaryImage()->remove();
	}

// start: confirmMail interface
	public function sendEmailCode()
	{
		$mailer = new \modules\clients\components\clientMailers\lib\ClientMailers($this);
		return $mailer->sendEmailCode()
			? $this->setEmailCodeSent()
			: false;
	}

	public function isSentEmailCode()
	{
		return (int)$this->emailCodeSent === 1;
	}

	public function isEmailConfirmed()
	{
		return (int)$this->emailConfirmed === 1;
	}

	private function setEmailCodeSent()
	{
		return $this->editField(1, 'emailCodeSent');
	}

	private function resetEmailCodeSent()
	{
		return $this->editField(0, 'emailCodeSent');
	}

	public function getCodeFromEmail()
	{
		return \core\utils\Utils::textToInt($this->getLogin());
	}

	public function confirmEmail($code)
	{
		return ( $this->getCodeFromEmail() == $code )
			? $this->editField(1, 'emailConfirmed')
			: false;
	}

	public function deconfirmEmail()
	{
		return $this->editField(0, 'emailConfirmed')
			? $this->resetEmailCodeSent()
			: false;
	}

// end: confirmMail interface

// start: confirmPhone interface
	public function sendPhoneCode()
	{
		$sms = new \modules\clients\components\clientSMS\lib\ClientSMS($this);
		return $sms->sendSMSCode()
			? $this->setPhoneCodeSent()
			: false;
	}

	public function isSentPhoneCode()
	{
		return (int)$this->phoneCodeSent === 1;
	}

	public function isPhoneConfirmed()
	{
		return (int)$this->phoneConfirmed === 1;
	}

	private function setPhoneCodeSent()
	{
		return $this->editField(1, 'phoneCodeSent');
	}

	private function resetPhoneCodeSent()
	{
		return $this->editField(0, 'phoneCodeSent');
	}

	public function getCodeFromPhone()
	{
		$mobile      = str_replace('+', '', $this->getMobile());
		$mobile      = str_replace(' ', '', $mobile);
		$mobile      = str_replace('(', '', $mobile);
		$mobile      = str_replace(')', '', $mobile);
		$mobileArray = str_split($mobile, 3);
		$phoneCode   = 0;

		foreach ($mobileArray as $symbol) {
			$phoneCode += (int)$symbol;
		}
		return $phoneCode;
	}

	public function confirmPhone($code)
	{
		return ( $this->getCodeFromPhone() == $code )
			? $this->editField(1, 'phoneConfirmed')
			: false;
	}

	public function deconfirmPhone()
	{
		return $this->editField(0, 'phoneConfirmed')
			? $this->resetPhoneCodeSent()
			: false;
	}
// end: confirmPhone interface

	public function getCodeFromPassword()
	{
		return \core\utils\Utils::textToInt($this->getPassword());
	}

	public function addLang($langId)
	{
		return $this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->id)->add($langId);
	}

	public function removeLang($langId)
	{
		return $this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->id)->remove($langId);
	}

	public function setMainLang($langId)
	{
		return $this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->id)->setMainLang($langId);
	}

	public function getMainLang()
	{
		return $this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->id)->getMainLang();
	}

	public function getLang()
	{
		return $this->getObject('\modules\clients\components\clientLangs\lib\ClientLangs', $this->id)->getClientLangs();
	}

	public function isMainLang($lang)
	{
		if(!$this->getMainLang())
			return false;
		return $this->getMainLang()->id == $lang->id;
	}

	public function getSystemLang()
	{
		if($this->systemLangId)
			return $this->getObject('\modules\languages\lib\Language', $this->systemLangId);
		return $this->getObject('\modules\languages\lib\Languages')->getObjectByAlias(\core\i18n\LangHandler::getInstance()->getDefaultLang());
	}

	public function isNotNeedBookingMail()
	{
		return !!$this->notSendBookingMails;
	}
	
	public function isNotNeedPublicityMail()
	{
		return !!$this->notSendPublicityMails;
	}
	
	public function isConfirmed() {
		return ( $this->isEmailConfirmed() && $this->isPhoneConfirmed() );
	}
}
