<?php
namespace core\modules\groups;
class Group extends \core\modules\base\ModuleObject
{
	use \core\modules\rights\RightsListTraitDecorator;

	protected $configClass = '\core\modules\groups\GroupConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass());
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}
}