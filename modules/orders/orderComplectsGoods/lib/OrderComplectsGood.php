<?php
namespace modules\orders\orderComplectsGoods\lib;
class OrderComplectsGood extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new OrderComplectsGoodObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \modules\parameters\components\parametersValues\lib\ParametersValuesRelationDecorator($object);
		$object = new \modules\properties\components\propertiesValues\lib\RelationsDecorator($object);
		parent::__construct($object);
	}

	public function getGood()
	{
		return $this->goodId
			? $this->getObject($this->class, $this->goodId)
			: $this->getNoop();
	}
}