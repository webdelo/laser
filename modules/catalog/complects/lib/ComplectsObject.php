<?php
namespace modules\catalog\complects\lib;
class ComplectsObject extends \modules\catalog\CatalogRegistrator
{
	protected $configClass     = '\modules\catalog\complects\lib\ComplectConfig';
	protected $objectClassName = '\modules\catalog\complects\lib\Complect';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
