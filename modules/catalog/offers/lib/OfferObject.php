<?php
namespace modules\catalog\offers\lib;
class OfferObject extends \modules\catalog\CatalogGoodObject
{
	protected $configClass = '\modules\catalog\offers\lib\OfferConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}