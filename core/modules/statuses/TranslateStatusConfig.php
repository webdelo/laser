<?php
namespace core\modules\statuses;
class TranslateStatusConfig extends \core\modules\base\ModuleConfig
{	
	protected $objectClass = '\core\modules\statuses\TranslateStatus';
	protected $objectsClass = '\core\modules\statuses\TranslateStatuses';
	
	protected  $tablePostfix = '_statuses'; // set value without preffix!
}