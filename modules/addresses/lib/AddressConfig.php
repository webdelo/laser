<?php
namespace modules\addresses\lib;
class AddressConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Alias;

	protected $objectClass = '\modules\addresses\lib\Address';
	protected $objectsClass = '\modules\addresses\lib\Addresses';

	protected $tablePostfix = '_addresses';
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'cityId',
		'index',
		'street',
		'home',
		'block',
		'flat',
		'latitude',
		'longitude'
	);

	public function rules()
	{
		return array(
			'cityId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true, 'positive'=>true)),
			),
			'street,building,index,block,flat,longitude,latitude' => array(
				'adapt' => '_adaptHtml',
			)
		);
	}

}