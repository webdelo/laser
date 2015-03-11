<?php
namespace modules\parameters\components\parametersValues\lib;
class ParametersValuesRelation extends \core\modules\base\ModuleDecorator
{
	private $parametersByAliasesCache = array();
		
	function __construct($objectId, $configObject)
	{
		$object = new ParametersValuesRelationObject($objectId, $configObject);
		parent::__construct($object);
	}
	
	function getParameterByAlias($alias)
	{
		if (isset($this->parametersByAliasesCache[$this->ownerId.$alias]))
			return $this->parametersByAliasesCache[$this->ownerId.$alias];

		$mainTable = $this->getObject('\modules\parameters\lib\Parameters')->mainTable();
		$this->resetFilters()
			 ->setSubquery(' AND `ownerId`=?d AND `parameterId`= ( SELECT `id` FROM `'.$mainTable.'` WHERE `alias`=\'?s\') ', $this->ownerId, $alias);
		
		$this->parametersByAliasesCache[$this->ownerId.$alias] = $this->current();
		return $this->parametersByAliasesCache[$this->ownerId.$alias];
	}
}