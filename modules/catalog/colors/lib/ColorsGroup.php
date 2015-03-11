<?php
namespace modules\catalog\colors\lib;
class ColorsGroup extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{	
		$object = new ColorsGroupObject($objectId, $configObject);
		parent::__construct($object);
	}
	

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->name;
	}
	/*   End: Main Data Methods */
}