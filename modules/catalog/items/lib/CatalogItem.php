<?php
namespace modules\catalog\items\lib;
class CatalogItem extends \modules\catalog\CatalogGood implements \interfaces\IObjectToFrontend, \interfaces\IGoodForShopcart
{
	use \core\traits\RequestHandler;

	function __construct($objectId)
	{
		$object = new CatalogItemObject($objectId);
		$object = new \modules\catalog\categories\CatalogCategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \modules\catalog\prices\lib\PricesDecorator($object);
		$object = new \modules\catalog\availability\lib\AvailabilityListDecorator($object);
		$object = new \modules\catalog\domainsInfo\lib\DomainsInfoDecorator($object);
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \core\modules\categories\AdditionalCategoriesDecorator($object);
		$object = new \modules\catalog\offers\lib\OfferDecorator($object);
		$object = new \modules\parameters\components\parametersValues\lib\ParametersValuesRelationDecorator($object);
		$object = new \modules\properties\components\propertiesValues\lib\RelationsDecorator($object);
		$object = new \modules\catalog\subGoods\lib\SubGoodsDecorator($object);
		$object = new \modules\fabricators\lib\FabricatorDecorator($object);
		parent::__construct($object);
	}

	public function edit ($data, $fields = array())
	{
		if ( $this->getParameters()->edit($data->parametersValues) )
			if ( $this->additionalCategories->edit($data->additionalCategories) )
				return $this->getParentObject()->edit($data, $fields);

		return false;
	}

	/* Start: Meta Methods */
	public function getMetaTitle()
	{
		return $this->getDomainInfoByDomainAlias($this->getCurrentDomainAlias())->getMetaTitle();
	}

	public function getMetaDescription()
	{
		return $this->getDomainInfoByDomainAlias($this->getCurrentDomainAlias())->getMetaDescription();
	}

	public function getMetaKeywords()
	{
		return $this->getDomainInfoByDomainAlias($this->getCurrentDomainAlias())->getMetaKeywords();
	}

	public function getHeaderText()
	{
		return $this->getDomainInfoByDomainAlias($this->getCurrentDomainAlias())->getHeaderText();
	}
	/*   End: Meta Methods */

	/* Start: URL Methods */
	public function getPath()
	{
		return $this->getPathByDomainAlias($this->getGoodDomain());
	}
	public function getGoodDomain()
	{
		return "run-laser.com";
	}
	/*   End: URL Methods */

	public function getAdminURL()
	{
		return '/admin/catalog/catalogItem/'.$this->id;
	}

	public function getPathByDomainAlias($domainAlias)
	{
		return $this->getCategory()->getPath().$this->getDomainInfoByDomainAlias($domainAlias)->alias.'/';
	}

	public function isValidPath($path)
	{
		return $this->getPath() == rtrim($path,'/').'/';
	}

	public function getDeliveryPrice()
	{
		if ($this->deliveryPrice) {
			return $this->deliveryPrice;
		} else {
			$deliveryId = (new \modules\deliveries\lib\DeliveryConfig())->getStandartPriceDeliveryId();
			$deliveryObject = (new \modules\deliveries\lib\Delivery($deliveryId)); 
			return $deliveryObject->getPrice();
		}
	}
	
	/* Start: IGoodForShopcart Methods */
	public function getMinQuantity()
	{
		return $this->isPricesExists()
			? $this->getPrices()->getMinQuantity()
			:1;
	}

	private function isPricesExists()
	{
		return (bool)$this->getPrices()->count();
	}

	public function getPriceByQuantity($quantity)
	{
		return $this->isPricesExists()
			? $this->getPrices()->getPriceByQuantity($quantity)->getPrice()
			: $this->getSubGoods()->getCost()*$quantity;
	}

	public function getPriceByMinQuantity()
	{
		return $this->isPricesExists()
			? $this->getPriceByQuantity($this->getMinQuantity())
			: $this->getSubGoods()->getCost();
	}

	public function getPathToShopcartGoodTemplate()
	{
		 return $this->getConfig()->shopcartTemplate;
	}
	/* End: IGoodForShopcart Methods */

	/* Start: IGoodForOrder Methods */
	public function getBasePriceByQuantity($quantity)
	{
		return $this->isPricesExists()
			? $this->getPrices()->getPriceByQuantity($quantity)->getBasePrices()->getMinBasePrice()->price
			: $this->getSubGoods()->getBaseCost()*$quantity;
	}

	public function getBasePriceByMinQuantity()
	{
		return $this->isPricesExists()
			? $this->getPrices()->getPriceByQuantity($this->getMinQuantity())->getBasePrices()->getMinBasePrice()->price
			: $this->getSubGoods()->getBaseCost();
	}

	public function getBasePriceByQuantityForPartner($quantity, $partnerId)
	{
		return $this->isPricesExists()
			? $this->getPrices()->getPriceByQuantity($this->getMinQuantity())->getBasePrices()->getMinBasePrice()->price
			: $this->getSubGoods()->getBaseCostByPartner($partnerId);
	}

	public function getPathToOrderGoodTemplate()
	{
		 return $this->getConfig()->orderTemplate;
	}

	public function getPathToAdminOrderGoodTemplate()
	{
		return $this->getConfig()->orderGoodAdminTemplate;
	}

	public function getTotalAvailability()
	{
		return $this->getAvailabilityList()->getTotalQuantity();
	}
	/* End: IGoodForOrder Methods */

	public function getInfo()
	{
		return $this->getDomainInfoByDomainAlias($this->getCurrentDomainAlias());
	}

	public function getAvailabilityList()
	{
		return $this->getParentObject()->getAvailabilityList();
	}

	/* Start: Sitemap Methods */
	public function getLastUpdateTime()
	{
		return $this->lastUpdateTime;
	}

	public function getSitemapPriority()
	{
		return empty($this->sitemapPriority) ? '0.5' : $this->sitemapPriority;
	}

	public function getChangeFreq()
	{
		return empty($this->changeFreq) ? 'weekly' : $this->changeFreq;
	}
	/*   End: Sitemap Methods */

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	public function isZeroPrice()
	{
		return $this->getPrices()->getMinPrice()->getPrice() == 0;
	}

	public function isNotZeroPrice()
	{
		return ! $this->isZeroPrice();
	}

	public function getAdminPath()
	{
		return '/admin/catalog/catalogItem/'.$this->id.'/';
	}

	public function getNoImagePath()
	{
		return '/images/noimage/no_image_file_main.png';
	}
	
	public function getNoImagePathAdditional()
	{
		return '/images/noimage/no_image_file_additional.png';
	}
	
	public function getNoImagePathBig()
	{
		return '/images/noimage/no_image_file_item.png';
	}
	
	public function getProductId()
	{
		return $this->id;
	}	
	
	public function getSeriaObject()
	{
		return isset($this->seriaId) && !empty($this->seriaId)
				?
					$this->getObject('\modules\parameters\components\parametersValues\lib\ParameterValue', $this->seriaId)
				:
					false;
	}
}