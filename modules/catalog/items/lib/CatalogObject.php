<?php
namespace modules\catalog\items\lib;
class CatalogObject extends \modules\catalog\CatalogRegistrator
{
	use \modules\catalog\traits\CatalogWordsSearch;
	protected $configClass = '\modules\catalog\items\lib\CatalogItemConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
