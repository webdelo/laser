<?php
namespace modules\catalog\availability\lib;
class AvailabilityConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
	    \core\traits\adapters\Base,
	    \core\traits\adapters\User,
	    \core\traits\outAdapters\OutDate;

	protected $objectClass  = '\modules\catalog\availability\lib\Availability';
	protected $objectsClass = '\modules\catalog\availability\lib\AvailabilityList';

	public $templates  = 'modules/catalog/availability/tpl/';
	public $imagesPath = '';

	protected $tablePostfix = '_availability'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'objectId',
		'partnerId',
		'quantity',
		'manufacturer',
		'comment',
		'lastUpdate',
		'userId',
	);

	public function rules()
	{
		return array(
			'comment' => array(
				'adapt' => '_adaptHtml',
			),
			'objectId, partnerId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'quantity' => array(
				'validation' => array('_validInt'),
			),
			'manufacturer' => array(
				'adapt' => '_adaptBool'
			),
			'lastUpdate' => array(
				'adapt' => '_adaptRegDate',
			),
			'userId' => array(
				'validation' => array('_validInt'),
				'adapt' => '_adaptUser',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'lastUpdate' => array('_outDateTime')
		);
	}
}