<?php
namespace modules\catalog\basePrices\lib;
class BasePrices extends \core\modules\base\ModuleDecorator
{
	function __construct($object)
	{	
		$object = new BasePricesObject($object);
		parent::__construct($object);
	}
}