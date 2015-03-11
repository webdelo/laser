<?php
namespace modules\partners\lib;
class Partners extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new PartnersObject;
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}

	protected function getActivePartners()
	{
		$partners = new PartnersObject;
		$config = $partners->getConfig();
		return $partners->setSubquery('AND `statusId`=?d', $config::STATUS_ACTIVE);
	}
}