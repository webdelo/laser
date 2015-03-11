<?php
namespace modules\administrators\lib;
class Administrator extends \core\authorization\AuthorizatedUser
{
	function __construct($objectId)
	{
		$object = new AdministratorObject($objectId);
		$object = new \core\modules\groups\GroupDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\rights\RightsListDecorator($object);
		parent::__construct($object);
	}

	public function delete()
	{
		if ($this->rights->delete())
			return $this->getParentObject()->delete();
		return false;
	}

	public function getAllName()
	{
		return trim(( $this->name && $this->firstname ) ? $this->name.' '.$this->firstname.' '.$this->lastname: $this->getLogin());
	}
}