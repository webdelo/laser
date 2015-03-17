<?php
namespace core\modules\rights;
class Right extends \core\modules\base\ModuleObject
{
	use \core\modules\base\ParentTraitDecorator;

	protected $configClass = '\core\modules\rights\RightConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass());
	}

}