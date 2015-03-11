<?php
namespace modules\catalog\complects\lib;
class ComplectObject extends \modules\catalog\CatalogGoodObject
{
	protected $configClass = '\modules\catalog\complects\lib\ComplectConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}