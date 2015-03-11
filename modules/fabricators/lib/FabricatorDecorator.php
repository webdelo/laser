<?php
namespace modules\fabricators\lib;
class FabricatorDecorator extends \core\modules\base\ModuleDecorator
{
	public $fabricator;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getFabricator()
	{
		if(empty($this->fabricator))
			$this->fabricator = $this->getObject('\modules\fabricators\lib\Fabricators')->getObjectById($this->fabricatorId);
		return $this->fabricator;
	}
}