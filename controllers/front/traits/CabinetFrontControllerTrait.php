<?php
namespace controllers\front\traits;
trait CabinetFrontControllerTrait
{
	public function getMenu()
	{
		$this->setContent('menu', new \modules\cabinet\lib\CabinetMenu)
			 ->includeTemplate('cabinet/menu');
		return $this;
	}

	public function getContent($action)
	{
		$this->includeTemplate('cabinet/'.$action);
		return $this;
	}

	public function getActiveRealties()
	{
		$realties = $this->getController('realties')->getRealtiesObject();
		$realties->setSubquery(' AND `clientId` = ?d ', $this->getAuthorizatedUserId());
		return $realties;
	}

	protected function getRealties()
	{
		return $this->getAuthorizatedUser()->getRealties();
	}

	protected function getRealtiesStatuses()
	{
		$statuses = $this->getAuthorizatedUser()->getRealties()->getStatuses();
		$statuses->setSubquery(' AND `id` NOT IN (6, 3)');
		return $statuses;
	}

	protected function getBookingList()
	{
		return $this->getAuthorizatedUser()->getBookings();
	}

	protected function isCurrentPart($current, $row)
	{
		$currentExploded = explode('/', $current);
		$current = array_pop($currentExploded);
		return ( $current == $row );
	}
}
