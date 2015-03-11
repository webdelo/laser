<?php
namespace core\modules\categories;
class AdditionalCategoriesConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\core\modules\categories\Category';
	protected $objectsClass = '\core\modules\categories\Categories';

	protected $tablePostfix = '_additional_categories'; // set value without preffix!
	protected $idField = 'objectId';
	protected $objectFields = array(
		'id',
		'ownerId',
		'objectId',
	);

}
