<?php
namespace modules\catalog\offers\lib;
class OffersObject extends \modules\catalog\CatalogRegistrator
{
	protected $configClass = '\modules\catalog\offers\lib\OfferConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
