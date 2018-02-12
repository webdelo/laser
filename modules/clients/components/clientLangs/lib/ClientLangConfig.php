<?php
namespace modules\clients\components\clientLangs\lib;
class ClientLangConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base;

	protected $objectClass = '\modules\clients\components\clientLangs\lib\ClientLang';
	protected $objectsClass = '\modules\clients\components\clientLangs\lib\ClientLang';

	protected $table = 'client_languages'; // set value without preffix!
	protected $idField = 'id';

	protected $objectFields = array(
		'id',
		'clientId',
		'languageId',
		'isMain'
	);

	public function rules()
	{
		return array(
			'id' => array(
				'adapt' => '_adaptId'
			),
			'clientId, languageId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'isMain' => array(
				'adapt' => '_adaptBool',
			)
		);
	}
}
