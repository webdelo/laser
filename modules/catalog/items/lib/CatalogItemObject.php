<?php
namespace modules\catalog\items\lib;
class CatalogItemObject extends \modules\catalog\CatalogGoodObject
{
	protected $configClass = '\modules\catalog\items\lib\CatalogItemConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}