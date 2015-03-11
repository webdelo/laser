<?php
namespace controllers\admin;
class NotifierAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\ControllersHandler,
		\core\traits\controllers\Templates;

	public function getTopNotices()
	{
		$items = array();
		foreach($this->getOrderStatusesForTopNotifier() as $statusId=>$value){
			$quantity = $this->getController('Orders')->getOrdersQuantityByStatusId($statusId);
			if($quantity > 0){
				$_GET['statusId'] = $statusId;
				$items[] = array('quantity'=>$quantity, 'title'=>$value['title'], 'href'=>'/admin/orders/?form_in_use=true&statusId='.$statusId);
			}
		}

		$this->setContent('noticesItems', $items)
			->includeTemplate(TEMPLATES_ADMIN.'topNotices');
	}

	private function getOrderStatusesForTopNotifier()
	{
		$orderConfig = new \modules\orders\lib\OrderConfig();
		return array(
			$orderConfig::NEW_ORDER_STATUS_ID => array('title'=>'Новые заказы'),
			$orderConfig::START_PAYMENT_STATUS_ID => array('title'=>'Заказы ожидают счет'),
			$orderConfig::PAYMENT_INVOICE_STATUS_ID => array('title'=>'Заказы на оплате'),
			$orderConfig::ON_PRODUCTION_ORDER_STATUS_ID => array('title'=>'Заказы на производстве'),
			$orderConfig::ON_DELIVERY_ORDER_STATUS_ID => array('title'=>'Заказы доставляются'),
			$orderConfig::COMPLETED_ORDER_STATUS_ID => array('title'=>'Заказы выполнены'),
		);
	}
}