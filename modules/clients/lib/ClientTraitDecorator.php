<?php
/* Requires \core\traits\ObjectPool in parent object */
namespace modules\clients\lib;
trait ClientTraitDecorator
{
	private $client;

	public function getClient()
	{
		$this->checkClientTraits();
		if (empty($this->client)){
			$this->client = $this->clientId
				? $this->getObject('\modules\clients\lib\Client', $this->clientId)
				: $this->getNoop();
		}
		return $this->client;
	}

	private function checkClientTraits()
	{
		if (in_array('getObject', get_class_methods($this)))
			return $this;
		throw new \Exception('Requires implementation of the method "getObject" for trait "\core\modules\statuses\StatusTraitDecorator" in object '.get_class($this).'!');
	}
}
