<?php
namespace controllers\admin;
class PricesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Templates,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Authorization,
		traits\PartnersAdminControllerTrait,
		traits\CatalogAdminControllerTrait;

	protected $permissibleActions = array(
		'getPricesTemplate',
		'getPricesTable',
		'addPrice',
		'ajaxGetPricesTableBlock',
		'ajaxRemovePrice',
		'ajaxGetBasePricesBlock',
		'saveBasePrices',
		'savePrice'
	);

	protected function getPricesTemplate($objectId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		$this->setGoodObjectToContent($objectId)
			 ->includeTemplate($good->getPrices()->getConfig()->getAdminTemplateDir().'prices');
	}

	private function setGoodObjectToContent($objectId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		return $this->setContent('object', $good);
	}

	protected function getPricesTable($objectId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		$this->setGoodObjectToContent($objectId)
			 ->includeTemplate($good->getPrices()->getConfig()->getAdminTemplateDir().'pricesTable');
	}

	protected function getAddPriceBlock($objectId)
	{
		$this->checkUserRightAndBlock('construction_showAddPriceBlock', 'noMessage');
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		$this->includeTemplate($good->getPrices()->getConfig()->getAdminTemplateDir().'addPriceBlock');
	}

	protected function addPrice()
	{
		$this->checkUserRightAndBlock('construction_addPrices');
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['objectId']);
		return $this->setObject($good->getPrices())
					->ajax($this->modelObject->add($this->getPOST()));
	}

	protected function ajaxGetPricesTableBlock()
	{
		$this->getPricesTable($this->getPOST()['objectId']);
	}

	protected function ajaxRemovePrice()
	{
		$this->checkUserRightAndBlock('construction_removePrice');
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['objectId']);
		$this->ajaxResponse($good->getPrices()->getObjectById($this->getPOST()['priceId'])->delete());
	}

	protected function getPriceCalculator($objectId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		$this->includeTemplate($good->getPrices()->getConfig()->getAdminTemplateDir().'calculator');
	}

	protected function ajaxGetBasePricesBlock()
	{
		$this->checkUserRightAndBlock('construction_basePricesBlock');
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['objectId']);
		$partners = $this->getPartners();
		return $this->setContent('price', $good->getPrices()->getObjectById($this->getPOST()['priceId']))
					->setContent('partners', $partners)
					->includeTemplate($good->getPrices()->getConfig()->getAdminTemplateDir().'basePricesBlock');
	}

	protected function getPartners()
	{
		$partners = new \modules\partners\lib\Partners();
		if($this->isAuthorisatedUserAnManager()){
			$authorizatedManager = $this->getObject('\modules\managers\lib\Manager', $this->getAuthorizatedUser()->id);
			return $partners->setSubquery('AND `id` = ?d', $authorizatedManager->partnerId);
		}
		return $partners->setSubquery('AND `statusId` = ?d', \modules\partners\lib\PartnerConfig::STATUS_ACTIVE);
	}

	protected function saveBasePrices()
	{
		$this->checkUserRightAndBlock('construction_saveBasePrices');
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['objectId']);
		$basePrices = $good->getPrices()->getObjectById($this->getPOST()['priceId'])->getBasePrices();
		foreach($this->getPartners() as $partner){
			if ($this->getPOST()[$partner->id]) {
				$basePrice = $basePrices->getBasePriceByPartnerId($partner->id);
				if ($this->isNoop($basePrice)){
					$data = array(
						'objectId'  => $this->getPOST()['priceId'],
						'partnerId' => $partner->id,
						'price'     => $this->getPOST()[$partner->id],
						'history'	=> $this->getHistoryLine($this->getPOST()[$partner->id])
					);
					$basePrices->add($data);
				} else {
					if($basePrice->price != $this->getPOST()[$partner->id])
						$basePrice->setValue('history', $basePrice->history.$this->getHistoryLine($this->getPOST()[$partner->id]));
					$basePrice->setValue('price', $this->getPOST()[$partner->id])
							->edit();
				}
			} else {
			    $basePrices->getBasePriceByPartnerId($partner->id)->delete();
			}
		}
		$this->ajaxResponse(true);
	}

	private function getHistoryLine($price)
	{
		return date("m.d.y").' - '.$this->getAuthorizatedUser()->getLogin().' - '.$price." руб. \r\n";
	}

	protected function savePrice()
	{
		$this->checkUserRightAndBlock('construction_editPrice');
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['objectId']);
		$price = $good->getPrices()->getObjectById($this->getPOST()['priceId']);
		$data = array(
			'id'       => $this->getPOST()['priceId'],
			'price'    => $this->getPOST()['price'],
			'oldPrice' => $this->getPOST()['oldPrice'],
		);
		$price->edit($data, array('id', 'price', 'oldPrice'));
		$this->ajaxResponse(true);
	}
}
