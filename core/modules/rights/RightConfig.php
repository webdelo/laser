<?php
namespace core\modules\rights;
class RightConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\core\modules\rights\Right';
	protected $objectsClass = '\core\modules\rights\Rights';

	protected $table = 'rights'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'alias',
		'name',
		'parentId',
		'description',
	);

}
