<?php
namespace modules\parameters\lib;
class ParametersRelationDecorator extends \core\modules\base\ModuleDecorator
{
	public $parametersRelation;
	public $parametersRelationArray = array();

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getParametersRelation()
	{
		 if(empty($this->parametersRelation))
			$this->parametersRelation = new ParametersRelation($this->id, $this->_object);
		return $this->parametersRelation;
	}

	function getParametersRelationArray()
	{
		 if($this->parametersRelationArray == array())
			if (!empty($this->parametersRelation))
				foreach($this->parametersRelation as $category)
					$this->parametersRelationArray[] = $category->id;
		return $this->parametersRelationArray;
	}
}