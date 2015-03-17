<?php
//	Для вывода своего темплейта нужно переопределить два метода
//	1. getTemplateWithClient
//	2. getTemplateToSearchClient
//	В них нужно указать путь к другим файлам шаблонов
namespace controllers\admin\traits;
trait PricesDecoratorTrait
{	

	protected function ajaxGetPricesBlocks()
	{
		echo $this->getPricesBlocks($this->getGET()->objectId);
	}

	protected function getPricesBlocks($realtyId)
	{
		$this->setContent('object', $this->getObject($this->objectClass, $realtyId))
			 ->setContent('months', \core\utils\Months::getMonthsDeclension())
			 ->setContent('days', \core\utils\Months::getDaysByMonth(1))
			 ->includeTemplate('pricesBlocks');
	}
	
	protected function getNewPriceBlock($realtyId)
	{
		$this->setContent('object', $this->getObject($this->objectClass, $realtyId))
			 ->setContent('months', \core\utils\Months::getMonths())
			 ->setContent('days', \core\utils\Months::getDaysByMonth(1))
			 ->includeTemplate('newPriceBlock');
	}
	
	protected function getDaysByStartMonth()
	{
		
		$this->getDaysByMonth($this->getPOST()->startMonth);
	}
	
	protected function getDaysByEndMonth()
	{
		$this->getDaysByMonth($this->getPOST()->endMonth);
	}
		
	protected function getDaysByMonth($month)
	{
		$json = array();
		$days = \core\utils\Months::getDaysByMonth($month);
		foreach ($days as $day) {
			$json[] = array(
				'value' =>$day,
				'name'  =>$day
			);
		}
		echo json_encode($json);
	}


	protected function addPrice()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$this->setObject($object->getPrices())->ajax($this->modelObject->add($this->getPOST()), 'ajax', true);
	}
	
	protected function editPrice()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$this->ajaxResponse($object->getPrices()->getObjectById($this->getPOST()->id)->edit($this->getPOST()));
	}
	
	protected function deletePrice()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$this->ajaxResponse($object->getPrices()->getObjectById($this->getPOST()->id)->delete());
	}
}
