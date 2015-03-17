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
			$quantity = $this->getController('Realties')->GetRealtiesQuantityByStatus($statusId);
			if($quantity > 0){
				$_GET['statusId'] = $statusId;
				$items[] = array('quantity'=>$quantity, 'title'=>$value['title'], 'href'=>'/admin/realties/?form_in_use=true&statusId='.$statusId);
			}
		}

		$this->setContent('noticesItems', $items)
			 ->includeTemplate(TEMPLATES_ADMIN.'topNotices');
	}

	private function getOrderStatusesForTopNotifier()
	{
		$realtyStatuses = new \modules\realties\lib\RealtyConfig();
		return array(
			$realtyStatuses->addingStatus => array('title'=>'Объекты в процессе добавления'),
			$realtyStatuses->moderStatus => array('title'=>'Объекты на модерации'),
		);
	}
}