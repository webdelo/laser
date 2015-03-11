<?php
namespace modules\partners\lib;
class Partner extends \core\modules\base\ModuleDecorator implements \interfaces\IPartner
{
	function __construct($objectId)
	{
		$object = new PartnerObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\rights\RightsListDecorator($object);
		$object = new \modules\managers\lib\ManagersDecorator($object);
		$object = new \modules\modulesDomain\lib\RelationModuleDomainDecorator($object);
		parent::__construct($object);
	}

	public function remove () {
		if ($this->modulesDomain->delete())
			return ($this->delete()) ? (int)$this->id : false ;

		return false;
	}

	public function edit ($data, $fields= array()) {
		return ($this->modulesDomain->edit($data->modulesDomain)) ? $this->getParentObject()->edit($data, $fields) : false;
	}
}
