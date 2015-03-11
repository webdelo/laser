<?php
namespace modules\catalog\constructions\lib;
class ConstructionObject extends \modules\catalog\CatalogGoodObject
{
	protected $configClass = '\modules\catalog\constructions\lib\ConstructionConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}