<?php
namespace modules\managers\lib;
class Manager extends \core\authorization\AuthorizatedUser implements \interfaces\IManager
{
	function __construct($objectId)
	{
		$object = new ManagerObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\rights\RightsListDecorator($object);

		parent::__construct($object);
	}

	protected function isManagerBelongsToPartner($partnerId)
	{
		return $this->partnerId == $partnerId;
	}

	protected function getPartner()
	{
		return $this->getObject('\modules\partners\lib\Partner', $this->partnerId);
	}
}