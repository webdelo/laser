<?php
namespace modules\parameters\components\parametersValues\lib;
trait ParametersValuesRelationTraitDecorator
{
	private $parametersValues;
	private $parametersValuesArray = array();
	private $parametersValuesForAlias;

	public function getParameters()
	{
		if(empty($this->parametersValues))
			$this->parametersValues = new ParametersValuesRelation($this->id, $this);
		return $this->parametersValues;

	}

	public function getParametersArray()
	{
		if (!$this->parametersValuesArray)
			foreach($this->getParameters() as $value) {
				$this->parametersValuesArray[] = $value->id;
			}
		return $this->parametersValuesArray;
	}

	public function getParameter($alias)
	{
		if(empty($this->parametersValuesForAlias))
			$this->parametersValuesForAlias = new ParametersValuesRelation($this->id, $this);
		return $this->parametersValuesForAlias->getParameterByAlias($alias);
	}

	public function addParameter( $parameterId )
	{
		$post = array( 0 => $parameterId );
		return $this->getParameters()->add(new \core\ArrayWrapper($post));
	}

	public function removeParameter( $parameterId )
	{
		return $this->getParameters()->deleteByObjectId($parameterId);
	}

	public function hasParameterWithId($parameterId)
	{
		$quantity = \core\db\Db::getMysql()->row( ' SELECT * FROM `'.$this->getParameters()->mainTable().'` WHERE `ownerId` = '.(int)$this->id.' AND `objectId` = '.(int)$parameterId );
		return ($quantity);
	}
}