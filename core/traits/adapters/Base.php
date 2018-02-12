<?php
namespace core\traits\adapters;
trait Base
{
	public function _adaptHtml($key)
	{
		if (isset($this->data[$key]))
			$this->data[$key] = \core\utils\DataAdapt::textValid($this->data[$key]);
	}

	public function _adaptBool($key)
	{
		$this->data[$key] = (empty($this->data[$key])) ? 0 : 1;
	}

	public function _adaptUnset($key)
	{
		unset($this->data[$key]);
	}
	
	public function _adaptId($key)
	{
		if ( empty($this->data['id']) ) {
			$this->data[$key] = \core\db\Db::getMaxId($this->mainTable()) + 1;
		}
	}
	
	public function _adaptCodeLikeId($key)
	{
		if ( empty($this->data[$key]) ) {
			$this->data[$key] = $this->data[$this->idField];
		}
	}
	
	public function _adaptUnique($key)
	{
		if (  empty($this->data['id']) && \core\db\Db::getMySql()->isExist($this->mainTable(),  $key, $this->data[$key]) ) {
			$this->data[$key] = $this->_transformUnique($key);
		}
	}
	
	private function _transformUnique($key)
	{
		$field = (int)$this->data[$key];
		do {
			$field++;
		} while( \core\db\Db::getMySql()->isExist($this->mainTable(),  $key, $field));

		return $field;
	}

	public function _adaptInt($key)
	{
		$this->data[$key] = (int)$this->data[$key];
	}
	
	public function _adaptStatus($key)
	{
		$this->data[$key] = (isset($this->data[$key])) ? (int)$this->data[$key] : 1;
	}

	protected function _adapt()
	{
		foreach ($this->dataVar as $key) {
			if (!empty($this->dataRules[$key]['adapt'])) {
				$rule = $this->dataRules[$key]['adapt'];
				$object = (method_exists($this->_moduleConfig, $rule)) ? $this->_moduleConfig : $this;
				$object->$rule($key);
			}
		}
		if (!empty($this->error))
			return false;
		return true;
	}

	protected function _beforeChange($arr, $dataVar = null, $dataRules = array())
	{
		return ($this->_beforeChangeWithoutAdapt($arr, $dataVar, $dataRules)) ? $this->_adapt() : false;
	}

	protected function _beforeChangeWithoutAdapt($arr, $dataVar = null, $dataRules = array())
	{
		$this->_setRules($dataRules)
			 ->_setDataVar($dataVar)
			 ->_setRelations();
		if (!$this->setData($arr))
			return false;
		if (!$this->_validation())
			return false;
		return true;
	}
}