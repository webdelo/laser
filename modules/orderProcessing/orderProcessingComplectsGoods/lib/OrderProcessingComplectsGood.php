<?php
namespace modules\orderProcessing\orderProcessingComplectsGoods\lib;
class OrderProcessingComplectsGood extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new OrderProcessingComplectsGoodObject($objectId);
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