<?php
namespace core\observersStorage\sqlObserversStorage;
class SqlObserversStorage extends \core\modules\base\ModuleAbstract implements \interfaces\IObserversStorage
{
	private $observableObject;

	private $_observersArray = array();

	public function __construct(\interfaces\IObservable $observableObject)
	{
		parent::__construct();
		$this->observableObject = $observableObject;
	}

	private function loadObservers()
	{
		$filterGenerator = new \core\FilterGenerator;
		$filterGenerator->setSubquery('AND `ownerHash` = \'?s\'', $this->observableObject->getHash());
		$observersMySQLArray = $this->reset()->getAll('*', $filterGenerator->getFiltersArray());
		$this->instanceObservers($observersMySQLArray);
		return $this;
	}

	private function instanceObservers($observersMySQLArray)
	{
		foreach ($observersMySQLArray as $observerData) {
			$this->_observersArray[$observerData['observerHash']] = unserialize($observerData['observerSerialize']);
		}
		return $this;
	}

	/* Start: IObserversStorage Methods */
	public function attach(\interfaces\IObserver $observer)
	{
		$data = array(
			'ownerHash'   => $this->observableObject->getHash(),
			'observerHash'=> $observer->getHash(),
			'observerSerialize' => serialize($observer),
		);
		if ($this->baseAdd($data) === false){
			throw new \Exception('Error adding Observer in class '.get_class($this).'! (Errors:'.serialize($this->getErrors()).')');
		} else {
			$this->_observersArray[$observer->getHash()] = $observer;
		}

		return $this;
	}

	public function detach(\interfaces\IObserver $observer)
	{
		$query = '`ownerHash` = \'?s\' AND `observerHash` = \'?s\'';
		$data = array(
			$this->observableObject->getHash(),
			$observer->getHash()
		);
		if ($this->deleteByQuery($query, $data))
			unset($this->_observersArray[$observer->getHash()]);
		else
			throw new \Exception('Error removing Observer in class '.get_class($this).'!');
		return $this;
	}

	public function notify()
	{
		return $this;
	}

	public function getObserversArray()
	{
		$this->loadObservers();
		return $this->_observersArray;
	}

	/* Start: Iterator Methods */
	function rewind() {
		reset($this->_observersArray);
	}

	function current() {
		$this->loadObservers();
		return current($this->_observersArray);
	}

	function key() {
		return key($this->_observersArray);
	}

	function next() {
		next($this->_observersArray);
	}

	function valid() {
		$this->loadObservers();
		return !!(current($this->_observersArray));
	}
	/* End: Iterator Methods */
	/* End: IObserversStorage Methods */
}
