<?php
namespace modules\catalog\domainsInfo\lib;
class DomainsInfo extends \core\modules\base\ModuleDecorator
{
	function __construct($object)
	{	
		$object = new DomainsInfoObject($object);
		//$object = new \core\modules\images\ImagesDecorator($object);
		//$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}