<?php
namespace modules\catalog\availability\lib;
class AvailabilityListObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\ObjectPool;

	protected $configClass = '\modules\catalog\availability\lib\AvailabilityConfig';

	private $goodObject;
	private $totalQuantity;
	private $isManufacturingExists;

	function __construct($object)
	{
		parent::__construct(new $this->configClass($object));
		$this->setGoodObject($object)->setGoodFilters();
	}

	private function setGoodObject($object)
	{
		if (is_object($object)) {
			$this->goodObject = $object;
			return $this;
		}
		throw new Exception('Good object must be passed in '.get_class($this).'::__constructor()!');
	}

	private function setGoodFilters()
	{
		return $this->setSubquery('AND `objectId` = ?d', $this->goodObject->id);
	}

	public function setFilters($filterGenerator)
	{
		throw new Exception('Filters are automatically assigned in '.  get_class($this).'!');
	}

	public function resetFilters()
	{
		throw new Exception('Filters are automatically assigned in '.  get_class($this).'!');
	}

	public function getAvailabilityByPartnerId($partnerId)
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
		$data = array($partnerId, $this->goodObject->id);
		$result = \core\db\Db::getMysql()->rowAssoc($query, $data);
		if ($result['id'])
			return $this->getObjectById($result['id']);

		$objectId = $this->addNewAvailability($partnerId);
		return $this->getObjectById($objectId);
	}

	protected function addNewAvailability($partnerId)
	{
		$data = array(
			'partnerId'    => $partnerId,
			'comment'      => '',
			'objectId'     => $this->goodObject->id,
			'quantity'     => '0',
			'manufacturer' => '',
			'lastUpdate' => '',
		);

		return $this->add($data, array(
			'partnerId',
			'objectId',
			'comment',
			'quantity',
			'manufacturer',
			'lastUpdate',
			'userId',
		));
	}

	public function getTotalQuantity()
	{
		if (!isset($this->totalQuantity)){
			$query = '
				SELECT
					SUM(`quantity`) as `sum`
				FROM
					`'.$this->mainTable().'`
				WHERE
					`objectId` = ?d
			';
			$data = array($this->goodObject->id);
			$result = \core\db\Db::getMysql()->rowAssoc($query, $data);
			$this->totalQuantity = (int)$result['sum'];

		}
		return $this->totalQuantity;
	}

	public function isManufacturingExists()
	{
		if (!isset($this->isManufacturingExists)){
			$query = '
				SELECT
					COUNT(*) as `manufacturing`
				FROM
					`'.$this->mainTable().'`
				WHERE
						`objectId` = ?d
					AND
						`manufacturer` != 0
			';
			$data = array($this->goodObject->id);
			$result = \core\db\Db::getMysql()->rowAssoc($query, $data);
			$this->isManufacturingExists = !!$result['manufacturing'];

		}
		return $this->isManufacturingExists;
	}
}