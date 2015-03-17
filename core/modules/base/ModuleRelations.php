<?php
namespace core\modules\base;
abstract class ModuleRelations extends ModuleObjects
{
	protected $objectClassName;
	public $ownerId;

	function __construct($ownerId, $configObject)
	{
		parent::__construct($configObject);
		$this->setOwnerId($ownerId)->setRelationFilters();
	}

	private function setOwnerId($ownerId)
	{
		$this->ownerId = (int)$ownerId;
		return $this->checkOwnerId();
	}

	private function checkOwnerId()
	{
		if (empty($this->ownerId))
			throw new \Exception('OwnerId is empty in class '.get_class().'!');
		return $this;
	}

	private function setRelationFilters()
	{
		$this->filters->setSubquery('AND `ownerId` = "?d"', $this->ownerId);
		return $this;
	}

	public function add($data, $fields = null)
	{
		if ($fields)
			throw new \Exception('Field selection is not possible in ModuleRelations::add() in '.get_class($this).'!');
		if (is_object($data) || is_array($data)) {
			foreach ($data as $value) {
				$rowData = array(
					'ownerId'  => $this->ownerId,
					'objectId' => (int)$value,
				);
				$this->reset();
				ModuleObjects::add($rowData, array('ownerId', 'objectId'));
			}
			return true;
		}
		return false;
	}

	public function delete()
	{
		$query = '
			DELETE FROM
				`'.$this->mainTable.'`
			WHERE
				`ownerId` = ?d
			';
		return \core\db\Db::getMysql()->query($query, array($this->ownerId));
	}

	public function edit($data, $fields = array())
	{
		return ($this->delete())    ?    ( isset($data) ? $this->add($data) : true )    :    false;
	}

}