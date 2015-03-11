<?php
namespace modules\parameters\components\parametersValues\lib;
class ParameterValue extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new ParameterValueObject($objectId);
		parent::__construct($object);
	}
	
	public function getParameter()
	{
		if(empty($this->parameter))
			$this->parameter = $this->getObject('\modules\parameters\lib\Parameter', $this->parameterId);
		return $this->parameter;
	}

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->getParameter()->name;
	}
	public function getImagePath()
	{
		return $this->getParameter()->imagePath;
	}
	public function getValue()
	{
		return $this->value;
	}
	/*   End: Main Data Methods */

	public function remove () {
		return $this->delete();
	}
	
	public function delete () {
		return ( $this->deleteRelations() ) ? $this->getParentObject()->delete() : false ;
	}
	
	private function deleteRelations ()
	{
		foreach ($this->getConfig()->relations() as $table=>$field) {
			$query = ' DELETE FROM `'.$table.'` WHERE `'.$field['idField'].'`=?d ';
			if ( !\core\db\Db::getMysql()->query( $query, array($this->id) ) )  {
				return false;
			}
		}
		return true;
	}
}