<?php
namespace interfaces;
interface IObservable extends IHashing
{
	public function attachObserver(\interfaces\IObserver $observer);
	public function detachObserver(\interfaces\IObserver $observer);
	public function notify($updateType, $updateDescription = null);
}