<?php
namespace modules\cabinet\lib;
class CabinetTrips extends CabinetBase
{
	use \core\traits\controllers\Authorization;
	
	public function count()
	{
		return $this->getObjects()->count();
	}
	
	public function getObjects()
	{
		$objects = new \modules\bookings\lib\Bookings;
		$objects->filterByClientId($this->getAuthorizatedUserId());
		
		return $objects;
	}
	
	public function exists()
	{
		return $this->count() > 0;
	}
	
	public function countNotifications()
	{
		$objects = $this->getObjects();
		$objects->filterByStatusId($objects->getConfig()->newStatus);
		
		return $objects->count();
	}
	
	public function countMessages()
	{
		$objects = $this->getObjects();
		$count = 0;
		foreach ( $objects as $object ) {
			$messages = $object->getNewMessagesForMe();
			$count += $messages->count();
		}
		
		return $count;
	}
	
	public function validatePath()
	{
		return stripos(\core\url\UrlDecoder::getInstance()->getCurrenPageWithoutQueryString(), $this->getShortClassName());
	}
}