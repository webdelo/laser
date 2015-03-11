<?php
namespace modules\catalog\availability\automaticUpdates;
class Updater
{
	private $updateUserId   = 130;
	private $resultUpdating = array();
	private $unknownGoods   = array();
	private $parser;

	public function __construct($parser)
	{
		$this->setParser($parser);
	}

	private function setParser($parser)
	{
		if (!is_subclass_of($parser, '\modules\catalog\availability\automaticUpdates\BaseParser'))
			throw new \Exception('Passed object is not implement abstract class Parser in class '.get_class($this).'!');
		$this->parser = $parser;
		return $this;
	}

	public function startAvailabilityUpdate()
	{
		return $this->baseUpdate();
	}

	protected function baseUpdate($type = 'availability')
	{
		foreach($this->parser as $updateGood){
			$good = \modules\catalog\CatalogFactory::getInstance()->getGoodByCode($updateGood->getCode());
			$resultArray = array(
				'goodCode' => $updateGood->getCode(),
				'quantity' => $updateGood->getQuantity(),
				'price'    => $updateGood->getPrice(),
				'name'     => $updateGood->getName(),
			);
			if (is_object($good)) {
				$method = $type.'Update';
				$resultArray[$method.'Result'] = $this->$method($good, $updateGood);
				$this->resultUpdating[] = $resultArray;
			} else {
				$this->unknownGoods[] = $resultArray;
			}
		}
		return $this;
	}

	protected function availabilityUpdate($good, $updateGood)
	{
		$availability = $good->getAvailabilityList()->getAvailabilityByPartnerId($this->parser->getPartnerId());
		$availability->quantity        = $updateGood->getQuantity();
		$availability->userId = $this->updateUserId;
		$availability->lastUpdate      = null;
		return $availability->edit();
	}

	protected function priceUpdate($good, $updateGood)
	{
		$basePrice = $good->getPrices()->getPriceByMinQuantity()->getBasePrices()->getBasePriceByPartnerId($this->parser->getPartnerId());
		$basePrice->price = $updateGood->getPrice();
		return $basePrice->edit();
	}

	public function startPriceUpdate()
	{
		return $this->baseUpdate('price');
	}

	public function getResultUpdating()
	{
		return $this->resultUpdating;
	}

	public function getUnknownGoods()
	{
		return $this->unknownGoods;
	}
}