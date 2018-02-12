<?php
namespace core\modules\paymentStatuses;
class PaymentStatusConfig extends \core\modules\base\ModuleConfig
{	
	protected $objectClass = '\core\modules\paymentStatuses\PaymentStatus';
	protected $objectsClass = '\core\modules\paymentStatuses\PaymentStatuses';
	
	protected  $tablePostfix = '_paymentStatuses'; // set value without preffix!
}