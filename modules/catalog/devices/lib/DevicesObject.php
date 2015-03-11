<?php
namespace modules\catalog\devices\lib;
class DevicesObject extends \modules\catalog\CatalogRegistrator
{
	use \modules\catalog\traits\CatalogWordsSearch;
	protected $configClass = '\modules\catalog\devices\lib\DeviceConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
