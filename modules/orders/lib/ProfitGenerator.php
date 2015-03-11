<?php
namespace modules\orders\lib;
class ProfitGenerator extends \core\Model
{
	use	\core\traits\controllers\Templates,
		\core\traits\RequestHandler;

	const TEMPLATE_DIR = 'modules/orders/tpl/reports/';

	private $orders;

	function __construct($orders)
	{
		$this->setOrders($orders);
	}

	private function setOrders($orders)
	{
		if($this->isOrders($orders))
			$this->orders = $orders;
	}

	private function isOrders($orders)
	{
		if (get_class($orders) != "modules\orders\lib\Orders" )
			throw new Exception ( 'Try to set invalid variable in '.get_class($this).'!' );
		return true;
	}

	public function displayProfitTable()
	{
		$this->setContent('table', $this->getProfitTable())
			->setContent('partners', new \modules\partners\lib\Partners)
			->includeTemplate(DIR.self::TEMPLATE_DIR.'groupProfit');
	}

	public function getProfitTable()
	{
		ob_start();
		$this ->setContent('objects', $this->orders)
			->includeTemplate(DIR.self::TEMPLATE_DIR.'groupProfitContent');
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}
}
