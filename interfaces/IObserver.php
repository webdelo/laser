<?php
namespace interfaces;
interface IObserver extends IHashing, \Serializable
{
	public function update(\interfaces\IObservable $object, $updateType, $updateDescription = null);
}