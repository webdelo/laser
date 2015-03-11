<?php
namespace modules\parameters\components\parametersValues\lib;
class ParametersValuesRelationConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\modules\parameters\components\parametersValues\lib\ParameterValue';
	protected $objectsClass = '\modules\parameters\components\parametersValues\lib\ParameterValues';

	protected $tablePostfix = '_parameters_values_relation'; // set value without preffix!
	protected $idField = 'objectId';
	protected $objectFields = array(
		'id',
		'ownerId',
		'objectId',
		'parameterId'
	);

}
