<?php
namespace modules\catalog\constructions\lib;
class ConstructionsObject extends \modules\catalog\CatalogRegistrator
{
	use \modules\catalog\traits\CatalogWordsSearch;
	protected $configClass = '\modules\catalog\constructions\lib\ConstructionConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
