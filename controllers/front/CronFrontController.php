<?php
namespace controllers\front;
class CronFrontController extends \controllers\base\Controller
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

	public function transferEventsToArchive()
	{
		$this->getController('Events')->transferEventsToArchive();
	}

}