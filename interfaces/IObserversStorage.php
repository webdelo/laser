<?php
namespace interfaces;
interface IObserversStorage extends \Iterator
{
	public function __construct(\interfaces\IObservable $observableObject);
	public function attach(\interfaces\IObserver $observer); // return $this
	public function detach(\interfaces\IObserver $observer); // return $this
	public function getObserversArray(); // return IObservers array
}