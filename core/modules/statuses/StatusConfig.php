<?php
namespace core\modules\statuses;
class StatusConfig extends \core\modules\base\ModuleConfig
{	
	protected $objectClass = '\core\modules\statuses\Status';
	protected $objectsClass = '\core\modules\statuses\Statuses';
	
	protected  $tablePostfix = '_statuses'; // set value without preffix!
}