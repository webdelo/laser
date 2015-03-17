<?php
namespace modules\measures\lib;
class Measure extends \core\modules\base\ModuleObject
{
	use \core\modules\statuses\StatusTraitDecorator,
		\core\traits\ObjectPool,
		\core\modules\categories\CategoryTraitDecorator;

	protected $configClass = '\modules\measures\lib\MeasureConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function getDeclension($numbers)
	{
		return \core\utils\Utils::declension($numbers, array($this->declension1, $this->declension2, $this->declension3));
	}

	public function getShortName()
	{
		$this->loadObjectInfo();
		return $this->objectInfo['shortName'] ? $this->objectInfo['shortName'] : $this->name;
	}
}