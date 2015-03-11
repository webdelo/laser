<?php
namespace modules\parameters\lib;
class ParametersRelationObject extends \core\modules\base\ModuleRelations
{
	protected $configClass = '\modules\parameters\lib\ParametersRelationConfig';

	function __construct($ownerId, $configObject)
	{
		parent::__construct($ownerId, new $this->configClass($configObject));
	}
}