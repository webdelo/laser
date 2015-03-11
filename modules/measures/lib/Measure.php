<?php
namespace modules\measures\lib;
class Measure extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new MeasureObject($objectId);
		$object = new \core\modules\categories\CategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);

		parent::__construct($object);
	}
	
	public function getDeclension($numbers)
	{
		return \core\utils\Utils::declension($numbers, array($this->declension1,$this->declension2,$this->declension3));
	}
	
	public function getShortName()
	{
		return $this->shortName ? $this->shortName : $this->name;
	}
}