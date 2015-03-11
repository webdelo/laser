<?php
namespace modules\orderProcessing\lib;
class OrderProcessingItemConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\outAdapters\OutDate;

	const ACTIVE_STATUS_ID   = 1;
	const BLOCKED_STATUS_ID  = 2;

	protected $objectClass   = '\modules\orderProcessing\lib\OrderProcessingItem';
	protected $objectsClass  = '\modules\orderProcessing\lib\OrderProcessingItems';
	protected $removedStatus = 3;
	protected $table         = 'order_processing'; // set value without preffix!
	protected $idField       = 'id';
	public $templates        = 'modules/orderProcessing/tpl/';
	
	protected $objectFields = array(
		'id',
		'moduleId',
		'domain',
		'statusId',
		'description',
		'date',
		'priority',
		'deliveryId',
		'deliveryPrice',
		'deliveryDate',
		'deliveryTime',
		'information',
		'partnerId',
		'clientId',
		'managerId',
		'cashRate',
		'country',
		'company',
		'email',
		'region',
		'city',
		'street',
		'home',
		'block',
		'flat',
		'name',
		'surname',
		'patronimic',
		'phone',
		'mobile',
		'index',
		'promoCodeId',
		'promoCodeDiscount',
	);

	public function rules()
	{
		return array(
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'deliveryDate' => array(
				'adapt' => '_adaptRegDate',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'deliveryDate' => array('_outDate'),
		);
	}

}
