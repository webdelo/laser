<?php
namespace core\traits\adapters;
trait Priority
{
	public function _adaptPriority($key)
	{
		if (!isset($this->data[$key])) {
			$row = \core\db\Db::getMysql()->rowAssoc('SELECT MAX(`priority`) as `maxPriority` FROM `'.$this->mainTable().'`');
			$this->data[$key] = ++$row['maxPriority'];
		} else {
			$this->data[$key] = (int)$this->data[$key];
		}
	}
}