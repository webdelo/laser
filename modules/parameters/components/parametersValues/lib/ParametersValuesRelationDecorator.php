<?php
namespace modules\parameters\components\parametersValues\lib;
class ParametersValuesRelationDecorator extends \core\modules\base\ModuleDecorator
{
	private $parametersValues;
	private $parametersValuesArray = array();
	private $parametersValuesForAlias;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getParameters()
	{
		if(empty($this->parametersValues))
			$this->parametersValues = new ParametersValuesRelation($this->id, $this->_object);
		return $this->parametersValues;

	}

	function getParametersArray()
	{
		if (!$this->parametersValuesArray)
			foreach($this->getParameters() as $value) {
				$this->parametersValuesArray[] = $value->id;
			}
		return $this->parametersValuesArray;
	}

	function getParameter($alias)
	{
		if(empty($this->parametersValuesForAlias))
			$this->parametersValuesForAlias = new ParametersValuesRelation($this->id, $this->_object);
		return $this->parametersValuesForAlias->getParameterByAlias($alias);
	}
}