<?php
namespace modules\clients\lib;
class Client extends \core\authorization\AuthorizatedUser
{
    	function __construct($objectId)
	{
		$object = new ClientObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\rights\RightsListDecorator($object);
		parent::__construct($object);
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	protected function getAllName()
	{
		return trim($this->surname.' '.$this->name.' '.$this->patronimic);
	}

	protected function haveTestMail()
	{
		return strpos($this->getLogin(), '@test.test');
	}
}
