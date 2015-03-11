<?php
namespace modules\catalog\prices\lib;
class PricesObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\ObjectPool;

	protected $configClass = '\modules\catalog\prices\lib\PriceConfig';
	private $catalogObject;

	function __construct($object)
	{
		parent::__construct(new $this->configClass($object));
		$this->setCatalogObject($object)->setPricesFilters();
	}

	private function setCatalogObject($object)
	{
		if (is_object($object)) {
			$this->catalogObject = $object;
			return $this;
		}
		throw new Exception('Goods object must be passed in '.get_class($this).'::__constructor()!');
	}

	private function setPricesFilters()
	{
		$filtes = new \core\FilterGenerator();
		$filtes->setSubquery('AND `objectId` = ?d', $this->catalogObject->id);
		return parent::setFilters($filtes);
	}

	public function setFilters($filterGenerator)
	{
		throw new Exception('Filters are automatically assigned in '.  get_class($this).'!');
	}

	public function getPriceByQuantity($quantity)
	{
		$query = '
			SELECT
				`id`
			FROM
				`'.$this->mainTable().'`
			WHERE
					`quantity` = (
						SELECT
							MAX(`quantity`)
						FROM
							`'.$this->mainTable().'`
						WHERE
								`quantity` <= ?d
							AND
								`objectId` = ?d
					)
				AND
					`objectId` = ?d
			LIMIT 1
		';
		$data = array($quantity, $this->catalogObject->id, $this->catalogObject->id);
		$result = \core\db\Db::getMysql()->rowAssoc($query, $data);

		if ($result['id'])
			return $this->getObjectById($result['id']);
		throw new \exceptions\ExceptionPrice('Was not found price by quantity = "'.$quantity.'" for objectId="'.$this->catalogObject->id.'" in table '.$this->mainTable().'!');
	}
	
	public function getPriceByMinQuantity()
	{
		$query = '
			SELECT
				`id`, MIN(`quantity`)
			FROM
				`'.$this->mainTable().'`
			WHERE
				`objectId` = ?d
			LIMIT 1
		';
		$data = array($this->catalogObject->id, $this->catalogObject->id);
		$result = \core\db\Db::getMysql()->rowAssoc($query, $data);

		if ($result['id'])
			return $this->getObjectById($result['id']);
		return $this->getNoop();
	}

	public function getMinQuantity()
	{
		return $this->getMinFieldByIdField('quantity', $this->catalogObject->id, 'objectId');
	}

	public function getMinPrice()
	{
		$query = '
			SELECT
				`id`, MIN(`price`)
			FROM
				`'.$this->mainTable().'`
			WHERE
				`objectId` = ?d
			LIMIT 1
		';
		$data = array($this->catalogObject->id, $this->catalogObject->id);
		$result = \core\db\Db::getMysql()->rowAssoc($query, $data);
		if ($result['id'])
			return $this->getObjectById($result['id']);
		return $this->getNoop();
	}
}