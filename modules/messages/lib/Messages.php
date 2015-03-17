<?php
namespace modules\messages\lib;
class Messages extends \core\modules\base\ModuleObjects
{
	use \core\traits\controllers\Authorization;
	private $filterAnonymFunc = array();
	
	protected $configClass     = '\modules\messages\lib\MessageConfig';
	protected $objectClassName = '\modules\messages\lib\Message';

	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}
	
	public function add($data, $fields=array(), $rules=array())
	{
		$data = $this->validateText($data);
		return parent::add($data, $fields, $rules);
	}
	
	private function validateText($data)
	{
		foreach ( $this->getFilter() as $filterFunc ) {
			if ( $filterFunc($data) ) {
				$data['statusId'] = $this->getConfig()->moderStatus;
				return $data;
			}
		}

		return $data;
	}

	public function setFilter($filterAnonymFunc)
	{
		if ( is_array($filterAnonymFunc) ) {
			foreach ($filterAnonymFunc as $filterFunc) {
				array_push($this->filterAnonymFunc, $filterFunc);
			}
			return $this;
		}
		if ( func_get_args() ) {
			foreach (func_get_args() as $filterFunc) {
				array_push($this->filterAnonymFunc, $filterFunc);
			}
			return $this;
		}
		throw new \Exception('To use method setFilter you must transmit anonymous functions to check any fields');
	}
	
	public function getFilter()
	{
		return $this->filterAnonymFunc;
	}
	
	public function excludeStatuses()
	{
		$args = func_get_args();
		if ( $args ) {
			$this->setSubquery(' AND `statusId` NOT IN ( ?s ) ', implode(',', $args));
		}
		
		return $this;
	}
	
	public function filterModerStatus()
	{
		$statuses = array(
			$this->getConfig()->newStatus, 
			$this->getConfig()->activeStatus
		);
		$this->setSubquery(' AND (`statusId` IN (?s) OR ( `statusId` = ?d AND `clientId` = ?d ) OR ( `statusId` = ?d AND `clientId` = ?d )) ', implode(',', $statuses), $this->getConfig()->moderStatus, $this->getAuthorizatedUserId(), $this->getConfig()->blockedStatus, $this->getAuthorizatedUserId());
		
		return $this;
	}
	
	public function loadObjects()
	{
		$idArray = new \core\ArrayWrapper($this->getIdArray());
		$objectConfig = clone $this->_moduleConfig;
		$objectConfig->removePostfix();
		foreach ($idArray as $objId) {
			$id = $objId[$this->idField];
			$object = $objId['isSystem']
				? $this->getConfig()->getServiceObjectClass()
				: $this->getConfig()->getObjectClass();
			$this->objectsList[$id] = &\core\ObjectPool::getInstance()->getObject($object, $id, $objectConfig);
		}
		return $this->objectsList;
	}
	
	public function getIdArray()
	{
		return (empty($this->objectsIdArray)) ? $this->loadIdArray() : $this->objectsIdArray;
	}
	
	private function loadIdArray()
	{
		return $this->objectsIdArray = $this->reset()->getAll($this->idField.',isSystem', $this->getFilters());
	}
	
	private function getFilters ()
	{
		$this->setRemovedObjectsFilters();
		return $this->filters->getFiltersArray();
	}
}