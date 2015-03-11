<?php
namespace modules\parameters\lib;
class ParametersRelationConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\modules\parameters\lib\Parameter';
	protected $objectsClass = '\modules\parameters\lib\Parameters';

	protected $tablePostfix = '_parameters_relation'; // set value without preffix!
	protected $idField = 'objectId';
	protected $objectFields = array(
		'id',
		'ownerId',
		'objectId',
	);

}
