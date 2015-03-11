<?php
namespace controllers\front;
class AvailabilityFrontController extends \controllers\base\Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		$this->defaultAction();
	}

	protected function defaultAction()
	{
		$this->redirect404();
	}

	public function updateByUntilov()
	{
		$updater = $this->getUntilovUpdater(DIR.'availabilityUpdate/Untilov/availability.csv');
		$updater->startAvailabilityUpdate();
		$updater->startPriceUpdate();
		
		$updater = $this->getUntilovUpdater(DIR.'availabilityUpdate/Untilov/specialPrices.csv');
		$updater->startPriceUpdate();
	}
	
	private function getUntilovUpdater($path)
	{
		$untilovParser = new \modules\catalog\availability\automaticUpdates\UntilovParser($path);
		return new \modules\catalog\availability\automaticUpdates\Updater($untilovParser);
	}

}