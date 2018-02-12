<?php
namespace core\modules\rights;
class RightsListConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\core\modules\rights\Right';
	protected $objectsClass = '\core\modules\rights\Rights';
	
	protected $tablePostfix = '_rights'; // set value without preffix!
	protected $idField = 'objectId';
	protected $objectFields = array(
		'id', 
		'ownerId',
		'objectId',
	);

}
