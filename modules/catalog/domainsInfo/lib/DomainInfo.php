<?php
namespace modules\catalog\domainsInfo\lib;
class DomainInfo extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new DomainInfoObject($objectId);
		parent::__construct($object);
	}

	/* Start: Meta Methods */
	public function getMetaTitle()
	{
		return $this->metaTitle ? $this->metaTitle : $this->getName();
	}

	public function getMetaDescription()
	{
		return $this->metaDescription;
	}

	public function getMetaKeywords()
	{
		return $this->metaKeywords;
	}
	
	public function getHeaderText()
	{
		return $this->headerText;
	}
	/*   End: Meta Methods */

	/* Start: URL Methods */
	public function getPath()
	{
		throw new \Exception('Method DomainInfo::getPath() was not finished!');
	}
	/*   End: URL Methods */

	public function isValidPath($path)
	{
		return $this->getPath() == rtrim($path,'/').'/';
	}

	/* Start: IGoodForShopcart Methods */
	public function getMinQuantity()
	{
		return 1;
	}

	public function getPriceByQuantity($quantity)
	{
		return $this->price * $quantity;
	}

	public function  getPrice()
	{
		return $this->price;
	}
	/* End: IGoodForShopcart Methods */
	
	public function getGood()
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->catalogId);
	}
	
	public function getParams()
	{
		return \core\utils\Params::getParamsArray($this->description);
	}
	
	public function getSmallParams()
	{
		return \core\utils\Params::getParamsArray($this->smallDescription);
	}
}