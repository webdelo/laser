<?php
namespace modules\components\lib;
class Component extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new ComponentObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\images\ImagesDecorator($object);

		parent::__construct($object);
	}

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->name;
	}
	/*   End: Main Data Methods */

	public function remove () {
		return $this->delete();
	}
	
	public function delete () {
		if ($this->getParentObject()->delete()) {
			return ($this->getImagesByObjectId()->delete()) ? (int)$this->id : false ;
		} else {
			return false;
		}
	}

}