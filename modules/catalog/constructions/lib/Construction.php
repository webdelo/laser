<?php
namespace modules\catalog\constructions\lib;
class Construction extends \modules\catalog\CatalogGood implements \interfaces\IObjectToFrontend, \interfaces\IGoodForShopcart
{
	use \core\traits\RequestHandler;

	function __construct($objectId)
	{
		$object = new ConstructionObject($objectId);
		$object = new \modules\catalog\categories\CatalogCategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \modules\catalog\prices\lib\PricesDecorator($object);
		$object = new \modules\catalog\availability\lib\AvailabilityListDecorator($object);
		$object = new \modules\catalog\domainsInfo\lib\DomainsInfoDecorator($object);
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\categories\AdditionalCategoriesDecorator($object);
		$object = new \modules\catalog\offers\lib\OfferDecorator($object);
		$object = new \modules\parameters\components\parametersValues\lib\ParametersValuesRelationDecorator($object);
		$object = new \modules\properties\components\propertiesValues\lib\RelationsDecorator($object);
		$object = new \modules\catalog\subGoods\lib\SubGoodsDecorator($object);
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
		return 'go-informator.ru';
	}
	/*   End: URL Methods */

	public function getAdminURL()
	{
		return '/admin/constructions/construction/'.$this->id;
	}

	public function getPathByDomainAlias($domainAlias)
	{
		if ($this->getCategory()->getParent()->alias == 'igrovie_konstrukcii')
			return '/'.$this->getCategory()->alias.'/'.$this->getDomainInfoByDomainAlias($domainAlias)->alias.'/';
		return $this->getCategory()->getPath().$this->getDomainInfoByDomainAlias($domainAlias)->alias.'/';
	}

	public function isValidPath($path)
	{
		return $this->getPath() == rtrim($path,'/').'/';
	}

	/* Start: IGoodForShopcart Methods */
	public function getMinQuantity()
	{
		return $this->getPrices()->getMinQuantity();
	}

	public function getPriceByQuantity($quantity)
	{
		return $this->getPrices()->getPriceByQuantity($quantity)->getPrice();
	}

	public function getPriceByMinQuantity()
	{
		return $this->getPriceByQuantity($this->getMinQuantity());
	}

	public function getPathToShopcartGoodTemplate()
	{
		 return $this->getConfig()->shopcartTemplate;
	}
	/* End: IGoodForShopcart Methods */

	/* Start: IGoodForOrder Methods */
	public function getBasePriceByQuantity($quantity)
	{
		return $this->getPrices()->getPriceByQuantity($quantity)->getBasePrices()->getMinBasePrice()->price;
	}

	public function getBasePriceByQuantityForPartner($quantity, $partnerId)
	{
		return $this->getPrices()->getPriceByQuantity($quantity)->getBasePrices()->getBasePriceByPartnerId($partnerId)->price;
	}

	public function getBasePriceByMinQuantity()
	{
		return $this->getPrices()->getPriceByQuantity($this->getMinQuantity())->getBasePrices()->getMinBasePrice()->price;
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
		return '/admin/constructions/construction/'.$this->id.'/';
	}

	public function getDelivery()
	{
		if($this->delivery)
			return $this->delivery;
		if($this->getCategory()->delivery)
			return $this->getCategory()->delivery;
		if($this->getCategory()->getParent() && $this->getCategory()->getParent()->delivery)
			return $this->getCategory()->getParent()->delivery;
		if($this->getCategory()->getParent()->getParent() && $this->getCategory()->getParent()->getParent()->delivery)
			return $this->getCategory()->getParent()->getParent()->delivery;
		if($this->getCategory()->getParent()->getParent()->getParent() && $this->getCategory()->getParent()->getParent()->getParent()->delivery)
			return $this->getCategory()->getParent()->getParent()->getParent()->delivery;

		$articles = new \modules\articles\lib\Articles();
		$articlesConfig = $articles->getConfig();
		return $articles->getObjectByAlias($articlesConfig::DELIVERY_TERMS_CONSTRUCTION_ARTICLE_ALIAS)->text;
	}

	protected function getComponent()
	{
		return $this->componentId
				? $this->getObject('\modules\components\lib\Component', $this->componentId)
				: $this->getNoop();
	}
}