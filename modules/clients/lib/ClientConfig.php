<?php
namespace modules\clients\lib;
class ClientConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Email,
		\core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutDate;

	const STATUS_ACTIVE = 1;
	const REMOVED_STATUS_ID = 3;

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass = '\modules\clients\lib\Client';
	protected $objectsClass = '\modules\clients\lib\Clients';

	protected $table = 'clients'; // set value without preffix!
	protected $idField = 'id';
	public $objectFields = array(
		'id',
		'regDate',
		'regType',
		'statusId',
		'priority',
		'description',

		'name',
		'surname',
		'patronimic',
		'birthday',
		'birthDate',
		'birthMonth',
		'birthYear',
		'additionalEmails',

		'phone',
		'mobile',

		'newsSubscribed',
		'phoneConfirmed',
		'phoneCodeSent',
		'emailConfirmed',
		'emailCodeSent',

		'notSendBookingMails',
		'notSendPublicityMails',

		'ip',
		'systemLangId'
	);

	public $templates = 'modules/clients/tpl/';
	public $imagesPath = 'files/clients/images/';
	public $imagesUrl  = 'data/images/clients/';
	public $filesPath = 'files/clients/files/';
	public $filesUrl  = 'data/files/clients/';

	public function rules()
	{
		return array(
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'regDate' => array(
				'adapt' => '_adaptRegDate',
			),
			'regType' => array(
				'adapt' => '_adaptRegType',
			),
			'surname' => array(
				'validation' => array('_validCharacters', [ 'field'=>'surname' ] ),
				'adapt' => '_adaptHtml',
			),
			'name' => array(
				'validation' => array('_validCharacters', [ 'field'=>'name' ] ),
				'adapt' => '_adaptHtml',
			),
			'description, patronimic, phone' => array(
				'adapt' => '_adaptHtml',
			),
			'phone, mobile' => array(
				'adapt' => '_adaptPhone'
			),
			'additionalEmails' => array(
				'validation' => array('_validAdditionalEmails'),
				'adapt' => '_adaptAdditionalEmails',
			),
			'birthday' => array(
				'adapt' => '_adaptBirthday',
			),
			'newsSubscribed' => array(
				'adapt' => '_adaptBool',
			),
			'notSendBookingMails, notSendPublicityMails' => array(
				'adapt' => '_adaptBool',
			)
		);
	}

	public function outputRules()
	{
		return array(
			'birthday, regDate' => array('_outDate'),
		);
	}

	public function validLogin ($login, $notEmpty = false)
	{
		return ( $this->_validEmail($login, array('notEmpty'=>$notEmpty, 'key'=>'login')) == 'true' );
	}

	public function _adaptRegType($key)
	{
		$this->data[$key] = empty($this->data[$key]) ? 1 : (int)$this->data[$key];
	}

	public function _adaptPhone($key)
	{
		if (isset($this->data[$key])) {
			$objectClass = $this->getObjectClass();
			$object = new $objectClass($this->data['id']);
			if ( $this->data[$key] != $object->$key ) {
				$object->deconfirmPhone();
			}
		}
	}

	public function _validAdditionalEmails($mails)
	{
		if (empty($mails))
			return true;
		else {
			$mails = array_map('trim', explode(',',$mails));
			foreach ($mails as $mail) {
				if (empty(\core\utils\Utils::isEmail($mail)))
					return false;
			}
			return true;
		}
	}

	public function _adaptAdditionalEmails($key)
	{
		if (empty($this->data[$key])){
			$this->data[$key] = '';
		} else {
			$mails = array_map('trim', explode(',',$this->data[$key]));
			$this->data[$key] = implode(',', $mails);
		}
	}
}
