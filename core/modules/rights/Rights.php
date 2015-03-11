<?php
namespace core\modules\rights;
class Rights extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		parent::__construct(new RightsObject);
	}
}