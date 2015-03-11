<?php
namespace modules\catalog\offers\lib;
class Offer extends \modules\catalog\CatalogGood implements \interfaces\IGoodForShopcart, \interfaces\IGoodForOrder
{
	use \core\traits\RequestHandler;

	function __construct($objectId)
	{
		$object = new OfferObject($objectId);
		$object = new \core\modules\categories\CategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \core\modules\categories\AdditionalCategoriesDecorator($object);
		parent::__construct($object);
	}

	public function remove () {
		if ($this->additionalCategories->delete())
			return ($this->delete()) ? (int)$this->id : false ;

		return false;
	}

	public function edit ($data, $fields = array()) {
		return ($this->additionalCategories->edit($data->additionalCategories)) ? $this->getParentObject()->edit($data, $fields) : false;
	}

	public function getJQFormatedTime()
	{
		$time = explode('-', $this->time);
		return $time[2].', '.($time[1] - 1).', '.($time[0] +1 );
	}

	protected function getPrice()
	{
		return  $this->getGoodPrice()- $this->discount;
	}

	protected function getGoodPrice()
	{
		return $this->getGood()->getPrices()->getMinPrice()->getPrice();
	}

	protected function getGood()
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->goodId);
	}

	public function getName()
	{
		return $this->name;
	}

	public function getFirstPrimaryImage()
	{
		if($this->getImagesByCategoryAndStatus(array(2), array(1))->current())
			return $this->getImagesByCategoryAndStatus(array(2), array(1))->current();
		else
			return $this->getGood()->getFirstPrimaryImage();
	}

	public function getAdminURL()
	{
		return '/admin/offers/offer/'.$this->id;
	}

	public function getGoodDomain()
	{
		return $this->getGood()->getGoodDomain();
	}

	/* Start: IGoodForShopcart Methods */
	public function getMinQuantity()
	{
		return 1;
	}

	public function getPriceByQuantity($quantity)
	{
		return $this->getPrice();
	}

	public function getPriceByMinQuantity()
	{
		return $this->getPrice();
	}

	public function getPathToShopcartGoodTemplate()
	{
		return $this->getConfig()->shopcartTemplate;
	}

	public function getClass()
	{
		return $this->getConfig()->objectClass;
	}
	/* End: IGoodForShopcart Methods */

	/* Start: IGoodForOrder Methods */
	public function getBasePriceByQuantity($quantity){}
	public function getBasePriceByQuantityForPartner($quantity, $partnerId){}
	public function getBasePriceByMinQuantity(){}
	public function getPathToOrderGoodTemplate(){}
	public function getPathToAdminOrderGoodTemplate(){}
	public function getTotalAvailability(){}
	/* End: IGoodForOrder Methods */

	/* Start: URL Methods */
	public function getPath()
	{
		return $this->getGood()->getPath();
	}
	/*   End: URL Methods */


}
