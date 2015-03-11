<?php
namespace modules\clients\lib;
class Clients extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ClientsObject;
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}