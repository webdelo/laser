<?php
namespace modules\catalog\offers\lib;
class Offers extends \core\modules\base\ModuleDecorator
{
	use	\core\traits\RequestHandler;

	function __construct()
	{
		$object = new OffersObject();
		$object = new \core\modules\images\ImagesDecorator($object);
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}

	protected function getTypes()
	{
		return array(
			'по времени'=>'time',
			'по количеству'=>'quantity',
		);
	}

	protected function getValidOfferByGoodId($goodId)
	{
		$config = $this->getConfig();
		$res = $this->setSubquery('AND `goodId` = ?d', $goodId)
				->setSubquery('AND `statusId` = ?d', $config::ACTIVE_STATUS_ID)
				->setOrderBy('`priority` ASC')
				->setLimit('1');
		foreach ( $res as $offer )
			$offer = $offer;

		if(isset($offer)){
			if($offer->type == 'time')
				return $offer->time >= date('d-m-Y') ? $offer : false;
			if($offer->type == 'quantity')
				return $offer->quantity > 0 ? $offer : false;
		}

		return false;
	}

	protected function getValidOffers($limit)
	{
		$config = $this->getConfig();
		$res = $this->setSubquery('AND `statusId` = ?d', $config::ACTIVE_STATUS_ID)
				->setSubquery('AND `domain` = \'?s\'', $this->getCurrentDomainAlias())
				->setOrderBy('`priority` ASC')
				->setLimit($limit);
		return $res;
	}
}