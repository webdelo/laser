<?php
namespace modules\catalog\basePrices\lib;
class BasePricesObject extends \core\modules\base\ModuleObjects
{
	use	\core\traits\ObjectPool,
		\core\traits\controllers\Authorization;

	protected $configClass = '\modules\catalog\basePrices\lib\BasePriceConfig';

	private $priceObject;

	function __construct($object)
	{
		parent::__construct(new $this->configClass($object));
		$this->setPriceObject($object)->setPricesFilters();
	}

	private function setPriceObject($object)
	{
		if (is_object($object)) {
			$this->priceObject = $object;
			return $this;
		}
		throw new Exception('Price object must be passed in '.get_class($this).'::__constructor()!');
	}

	private function setPricesFilters()
	{
		$filtes = new \core\FilterGenerator();
		$filtes->setSubquery('AND `objectId` = ?d', $this->priceObject->id);
		return parent::setFilters($filtes);
	}

	public function setFilters($filterGenerator)
	{
		throw new Exception('Filters are automatically assigned in '.  get_class($this).'!');
	}

	public function resetFilters()
	{
		throw new Exception('Filters are automatically assigned in '.  get_class($this).'!');
	}

	public function getBasePriceByPartnerId($partnerId)
	{
		$query = '
			SELECT
				`id`
			FROM
				`'.$this->mainTable().'`
			WHERE
					`partnerId` = ?d
				AND
					`objectId` = ?d
			LIMIT 1
		';
		$data = array($partnerId, $this->priceObject->id);
		$result = \core\db\Db::getMysql()->rowAssoc($query, $data);
		if ($result['id'])
			return $this->getObjectById($result['id']);
		return $this->getNoop();
	}

	public function getMinBasePrice()
	{
		if($this->isAuthorisatedUserAnManager()){
			$authorizatedManager = $this->getAuthorizatedUser();
			$partner = $this->getObject('\modules\partners\lib\Partner', $authorizatedManager->partnerId);
			return $this->getBasePriceByPartnerId($partner->id);
		}

		$query = '
			SELECT
				`id`
			FROM
				`'.$this->mainTable().'`
			WHERE
					`price` = (SELECT MIN(`price`) FROM `'.$this->mainTable().'` WHERE `objectId` = ?d)
				AND
					`objectId` = ?d
			LIMIT 1
		';
		$data = array($this->priceObject->id, $this->priceObject->id);
		$result = \core\db\Db::getMysql()->rowAssoc($query, $data);
		if ($result['id'])
			return $this->getObjectById($result['id']);
		return $this->getNoop();
	}
}