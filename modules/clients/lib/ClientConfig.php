<?php
namespace modules\clients\lib;
class ClientConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Email,
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
		'name',
		'surname',
		'patronimic',
		'phone',
		'mobile',
		'birthday',
		'birthDate',
		'birthMonth',
		'birthYear',
		'addEmails',
		'country',
		'region',
		'city',
		'street',
		'home',
		'block',
		'flat',
		'company',
		'inn',
		'kpp',
		'ogrn',
		'description',
		'statusId',
		'priority',
		'index',
		'deliveryCountry',
		'deliveryIndex',
		'deliveryRegion',
		'deliveryCity',
		'deliveryStreet',
		'deliveryHome',
		'deliveryBlock',
		'deliveryFlat',
		'deliveryPerson'
	);

	public $templates = 'modules/clients/tpl/';

	public function rules()
	{
		return array(
			'name, surname, phone, country, street, home, city' => array(
				'validation' => array('_validNotEmpty'),
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'company, description' => array(
				'adapt' => '_adaptHtml',
			),
			'index' => array(
				'validation' => array('_validNumber'),
			),
			'birthday' => array(
				'adapt' => '_adaptBirthday',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'birthday' => array('_outDate'),
		);
	}

	public function validLogin ($login, $notEmpty = false) {
		return ( $this->_validEmail($login, array('notEmpty'=>$notEmpty, 'key'=>'login')) == 'true' );
	}
}