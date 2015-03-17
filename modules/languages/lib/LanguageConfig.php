<?php
namespace modules\languages\lib;
class LanguageConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\modules\languages\lib\Language';
	protected $objectsClass = '\modules\languages\lib\Languages';

	protected $table = 'languages'; // set value without preffix!
	protected $idField = 'id';

	protected $objectFields = array(
		'id',
		'name',
		'initName',
		'alias'
	);
}
