<?php
namespace controllers\admin;
class AvailabilityAdminController extends \controllers\base\Controller
{
	use traits\PartnersAdminControllerTrait,
		traits\CatalogAdminControllerTrait,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\ServiceRequests,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'getAvailabilityBlock',
		'printAvailabilityBlock',
		'ajaxGetAvailabilityBlock',
		'ajaxSaveAvailabilityForPartner',
	);

	protected function printAvailabilityBlock($objectId)
	{
		echo $this->getAvailabilityBlock($objectId);
	}

	public function ajaxPrintAvailabilityBlock()
	{
		return $this->printAvailabilityBlock($this->getREQUEST()['objectId']);
	}

	protected function getAvailabilityBlock($objectId)
	{
		ob_start();
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		$controller = (isset($this->getPOST()['controller'])) ? $this->getPOST()['controller'] : $this->getREQUEST()['controller'];
		$this->setGoodObjectToContent($objectId)
			 ->setContent('partners', $this->getPartnersByModule($controller))
			 ->includeTemplate($good->getAvailabilityList()->getConfig()->getAdminTemplateDir().'availabilityBlock');
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function ajaxSaveAvailabilityForPartner()
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['objectId']);
		$result = $good->getAvailabilityList()
				->getAvailabilityByPartnerId($this->getPOST()['partnerId'])
				->edit($this->getPOST(), array(
					     'partnerId',
					     'objectId',
					     'comment',
					     'quantity',
					     'manufacturer',
					     'lastUpdate',
					     'administratorId',
				     ));

		$this->ajaxResponse($result);
	}

}
