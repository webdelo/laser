<?php
namespace modules\deliveries\lib;
class Delivery extends \core\modules\base\ModuleDecorator
{
	use	\core\traits\controllers\Authorization;

	function __construct($objectId)
	{
		$object = new DeliveryObject($objectId);
		$object = new \core\modules\categories\CategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		parent::__construct($object);
	}

	public function getName()
	{
		return $this->getParentObject()->name;
	}

	public function getPrice()
	{
		return $this->price;
	}
	
	public function getBasePrice()
	{
		return $this->basePrice;
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	public function getDataString()
	{
		$config = $this->getConfig();
		$string = false;
		if ( in_array($this->getCategory()->id, array($config::POST_CATEGORY_ID, $config::TO_CLIENT_CATEGORY_ID)) ){
			$string = $this->getAuthorizatedUser()->deliveryIndex ? $this->getAuthorizatedUser()->deliveryIndex.', ' : '';
			$string .= $this->getAuthorizatedUser()->deliveryCountry.', ';
			$string .= $this->getAuthorizatedUser()->deliveryRegion ? $this->getAuthorizatedUser()->deliveryRegion.', ' : '';
			$string .= $this->getAuthorizatedUser()->deliveryCity.', ';
			$string .= $this->getAuthorizatedUser()->deliveryStreet.', ';
			$string .= $this->getAuthorizatedUser()->deliveryHome.', ';
			$string .= $this->getAuthorizatedUser()->deliveryBlock ? ' ( '.$this->getAuthorizatedUser()->deliveryBlock.' ) ' : '';
			$string .= $this->getAuthorizatedUser()->deliveryFlat ? $this->getAuthorizatedUser()->deliveryFlat.', ' : '';
			$string .= $this->getAuthorizatedUser()->deliveryPerson;;
		}
		return $string;
	}
}
