<?php
namespace modules\parameters\lib;
trait ParametersRelationTraitDecorator
{
	private $parametersRelation;
	private $parametersRelationArray = array();

	public function getParametersRelation()
	{
		if (empty($this->parametersRelation))
			$this->parametersRelation = new ParametersRelation($this->id, $this);
		return $this->parametersRelation;
	}

	public function getParametersRelationArray()
	{
		if (empty($this->parametersRelationArray))
			if (!empty($this->getParametersRelation())){
				$this->parametersRelationArray = array();
				foreach($this->getParametersRelation() as $category)
					$this->parametersRelationArray[] = $category->id;
			}
		return $this->parametersRelationArray;
	}
}