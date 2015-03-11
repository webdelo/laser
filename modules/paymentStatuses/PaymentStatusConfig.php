<?php
namespace modules\paymentStatuses;
class PaymentStatusConfig extends \core\modules\base\ModuleConfig
{	
	protected $objectClass = '\modules\paymentStatuses\PaymentStatus';
	protected $objectsClass = '\modules\paymentStatuses\PaymentStatuses';
	
	protected  $tablePostfix = '_paymentStatuses'; // set value without preffix!
}