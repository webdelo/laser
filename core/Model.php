<?php
namespace core;
abstract class Model
{
	use \core\traits\Errors,
		\core\traits\Settings,
		\core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base;

	const REL_ONE = 'one';
	const REL_MANY = 'many';
	protected $data;
	protected $query;
	protected $queryEnd;
	protected $queryData;
	protected $mainTable;
	protected $idField = 'id';
	protected $dataVar = array();
	protected $dataRules = array();
	protected $outputRules = array();
	protected $relations = array();
	protected $useRelations = true;
	protected $fields;
	protected $_moduleConfig;

	function __construct()
	{
		$this->mainTable = $this->mainTable();
	}

	public function mainTable()
	{
		return TABLE_PREFIX.strtolower(get_class($this));
	}

	protected function getIdField()
	{
		return $this->idField;
	}

	public function getConfig()
	{
		return $this->_moduleConfig;
	}

	public function rules()
	{
		return array();
	}

	public function outputRules()
	{
		return array();
	}

	public function relations()
	{
		return array();
	}

	public function reset()
	{
		$this->query = $this->queryData = $this->fields = $this->queryEnd = '';
		return $this;
	}

	public function resetData()
	{
		$this->data = $this->dataRules = $this->dataVar = null;
		return $this;
	}

	public function getAll($fields = '*', $filter = array(),$count = false)
	{
		if ($count === true) {
			$this->_filter2Query($filter);
			$data =  db\Db::getMysql()->queryCount($this->mainTable.' mt '.$this->query, $this->queryData);
			$this->reset();
			return $data;
		}
		$this->_beforeSelection($fields, $filter);
		$rows = db\Db::getMysql()->rowsAssoc('SELECT '.$this->_getFieldsString().' FROM '.$this->mainTable.' mt '.$this->query, $this->queryData);
		foreach ($rows as $key=>$row){
			$this->data = $row;
			$this->_afterSelection();
			$rows[$key] = $this->getData();
		}
		return $rows;
	}

	public function countAll($filter = array())
	{
		$this->_filter2Query($filter);
		$data = db\Db::getMysql()->queryCount($this->mainTable.' mt '.$this->query, $this->queryData);
		$this->reset();
		return $data;
	}

	public function getInfoById($id, $fields = '*')
	{
		$this->reset()->_beforeSelection($fields, array());
		$q = 'SELECT '.$this->_getFieldsString().' FROM '.$this->mainTable.' mt '.$this->query.' WHERE mt.'.$this->idField.'=?d';
		$this->queryData[] = $id;
		$this->data = db\Db::getMysql()->rowAssoc($q,$this->queryData);
		$this->_afterSelection();
		return $this->getData();
	}

	public function getOne($fields = '*', $filter = array())
	{
		$this->reset()->_beforeSelection($fields, $filter);
		$q = 'SELECT '.$this->_getFieldsString().' FROM '.$this->mainTable.' mt '.$this->query;
		$this->data = db\Db::getMysql()->rowAssoc($q,$this->queryData);
		$this->_afterSelection();
		return $this->getData();
	}

	public function getSum($field, $filter = array())
	{
		$this->reset()->_beforeSelection($field, $filter);
		$q = 'SELECT SUM(`'.$field.'`) FROM '.$this->mainTable.' mt '.$this->query;
		$this->data = db\Db::getMysql()->rowAssoc($q,$this->queryData);
		$this->_afterSelection();
		return $this->getData();
	}

	public function getHtmlList($value,$text,$needed_value = '',$filter = array())
	{
		$this->reset()->queryData = array($value,$text);
		$this->_filter2Query($filter);
		$rows = db\Db::getMysql()->rowsAssoc('SELECT ?s, ?s FROM '.$this->mainTable.' '.$this->query, $this->queryData);
		$data = array();
		$i = 0;
		foreach ($rows as $row) {
			$data[$i]['value'] = $row[$value];
			$data[$i]['text'] = $row[$text];
			$data[$i]['selected'] = ($row[$value] == $needed_value) ? 'selected' : '' ;
			$i++;
		}
		return $data;
	}

	protected function _setRelations()
	{
		if (empty($this->relations) && $this->useRelations) foreach ($this->relations() as $key=>$relation) {
			if (!$this->_isRelationClass($key)) $this->relations[$key] = $relation;
		}
		return $this;
	}

	public function changeRelations($new_relations = array())
	{
		if (empty($new_relations)) {
			$this->useRelations = false;
			return;
		}
		foreach ($new_relations as $key=>$new) {
			if (!empty($new) && !$this->_isRelationClass($new[1])) $this->relations[$key] = $new;
			elseif (empty($new)) $this->relations[$key] = $new;
		}
	}

	protected function _setOutputRules()
	{
		if (empty($this->outputRules)) $this->outputRules = $this->outputRules();
	}

	public function changeOutputRules($rules = array())
	{
		if (!empty($rules)) foreach ($rules as $key=>$rule)
			$this->outputRules[$key] = $rule;
		else
			$this->outputRules = array();
		return $this;
	}

	public function updateOutputRules($rules = array())
	{
		$this->outputRules = array();
		if (!empty($rules)) foreach ($rules as $key=>$rule){
			$this->outputRules[$key] = $rule;
		}
	}

	public function with($rel_keys = '')
	{
		if (empty($rel_keys)) {
			$this->useRelations = false;
			return $this;
		}
		$rel_keys = explode(',', $rel_keys);
		$relations = $this->relations();
		foreach($rel_keys as $key) {
			$key = trim($key);
			if (array_key_exists($key, $relations)) $this->relations[$key] = $relations[$key];
		}
		return $this;
	}

	protected function _setFields($fields)
	{
		if (empty($fields)) $fields = '*';
		$fields = explode(',', $fields);
		$this->fields[] = 'mt.`'.$this->idField.'`';
		foreach ($fields as $field) {
			$field = trim($field);
			$this->fields[] = ($field == '*')? 'mt.*' : 'mt.'.$field;
		}
		foreach ($this->relations as $key=>$relation) {
			if ($this->_isRelationClass($key) && !in_array($relation[2], $fields)) $this->fields[] = 'mt.`'.$relation[2].'`';
		}
	}

	protected function _getFieldsString()
	{
		return join(', ', $this->fields);
	}

	protected function _beforeSelection($fields, $filter)
	{
		$this->_setRelations();
		$this->_setFields($fields);
		if (!empty($this->relations)) $this->_relBefore();
		$this->_filter2Query($filter);
		$this->query .= $this->queryEnd;
	}

	protected function _afterSelection()
	{
		if (empty($this->data)) return;
		if (!empty($this->relations)) $this->_relAfter();
	}

	protected function _relBefore()
	{
		$i = 1;
		foreach ($this->relations as $key=>$relation) {
			if (empty($relation) || $this->_isRelationClass($key) || $relation[0] == self::REL_MANY) continue;
			$prefix = 'j_tab_'.$i;
			if ($relation[1] == '*') $this->fields[] = $prefix.'.*';
			else foreach ($relation[1] as $field=>$k) {
				$this->fields[] .= $prefix.'.`'.$field.'` as `'.$k.'`';
			}
			$this->query .= 'LEFT JOIN '.$key.' '.$prefix.' ON mt.'.$relation[2].'='.$prefix.'.'.$relation[3].' ';
			$i++;
		}
	}

	protected function _relAfter()
	{
		foreach ($this->relations as $key=>$relation) {
			$this->data = $this->_relAfterAction($this->data, $key, $relation);
		}
	}

	protected function _relAfterAction($data, $key, $relation)
	{
		if (empty($relation))
			continue;
		$fields = '';
		if ($relation[1] == '*')
			$fields = '*';
		else
			foreach ($relation[1] as $field=>$name)
				$fields .= '`'.$field.'` as `'.$name.'`,';

		$fields = substr($fields, 0, -1);
		if ($this->_isRelationClass($key)) {
			$obj = str_replace('{', '', $key);
			$obj = str_replace('}', '', $obj);
			$obj = new $obj;
            if (empty($data[$relation[2]]))
				$data[$relation[2]] = 0;
			$filter['where']['query'] = $relation[3].'='.$data[$relation[2]];
			if ($relation[0] == self::REL_MANY) {
				$rows = $obj->with()->getAll($fields,$filter);
				foreach ($rows as $row)
					foreach ($relation[1] as $field)
						$data[$field][] = $row[$field];
			}
			elseif ($relation[0] == self::REL_ONE) {
				$row = $obj->with()->getOne($fields,$filter);
				foreach ($relation[1] as $field)
					$data[$field] = $row[$field];
				if (!empty($relation[4]))
					$data = $this->_relAfterAction($data, key($relation[4]), $relation[4][key($relation[4])]);
			}
		}
		elseif ($relation[0] == self::REL_MANY) {
			$rows = db\Db::getMysql()->rowsAssoc('SELECT '.$fields.' FROM '.$key.' WHERE '.$relation[3].' = '.$data[$relation[2]]);
			if (!empty($relation[4])) foreach ($rows as $k=>$row) {
				$rows[$k] = $this->_relAfterAction($row, key($relation[4]), $relation[4][key($relation[4])]);
			}
			$data[$key] = $rows;
		}
		return $data;
	}

	protected function _isRelationClass($name)
	{
		return preg_match("|^\{.+\}$|", $name);
	}

	protected function _filter2Query($filter)
	{
		$filter = ($filter instanceof FilterGenerator) ? $filter->getFiltersArray() : $filter;
		if (!empty($filter['where'])) {
			$this->query .= ' WHERE ' . $filter['where']['query'];
			if (!empty($filter['where']['data'])) foreach ($filter['where']['data'] as $d)
				$this->queryData[] = $d;
		}
		if (!empty($filter['group_by']))
			$this->query .= ' GROUP BY ' . $filter['group_by'];
		if (!empty($filter['order_by']))
			$this->query .= ' ORDER BY ' . $filter['order_by'];
		if (!empty($filter['limit']))
			$this->query .= ' LIMIT ' . $filter['limit'];
	}

	public function getData($convert_data = true)
	{
		if ($convert_data)
			$this->_setOutputRules();
		if (empty($this->outputRules))
			return $this->data;
		if (!empty($this->data))
			$this->_convertOutput();
		return $this->data;
	}

	protected function _convertOutput()
	{
		foreach ($this->outputRules as $key=>$rule) {
			if (array_key_exists($key, $this->data)) {
				$rule[1] = (empty($rule[1])) ? '' : $rule[1];
				$object = (method_exists($this->_moduleConfig, $rule[0])) ? $this->_moduleConfig : $this;
				$this->data[$key] = $object->$rule[0]($this->data[$key],$rule[1]);
			}
		}
	}

	public function baseAdd($arr, $dataVar = null, $data_set = array())
	{
		if ($arr instanceof ArrayWrapper)
			$arr = $arr->getArray();
		if (empty($dataVar))
			$dataVar = array_keys($arr);
		if (!$this->_beforeChange($arr, $dataVar, $data_set))
			return false;

		return $this->_addRecord($this->mainTable, $this->data);
	}

	public function baseEdit($arr, $dataVar = null, $data_set = array())
	{
	    if ($arr instanceof ArrayWrapper)
			$arr = $arr->getArray();

		if (empty($dataVar))
			$dataVar = array_keys($arr);

		if (!$this->_beforeChange($arr, $dataVar, $data_set))
			return false;

		$this->data[$this->idField] = (isset($this->data[$this->idField])) ? intval($this->data[$this->idField]) : null;
		return $this->_editRecord($this->mainTable, $this->data, $this->idField);
	}

	public function baseDelete($arr, $dataVar, $data_set = array())
	{
		if (!$this->_beforeChange($arr, $dataVar, $data_set)) return false;

		return $this->_deleteRecord($this->mainTable, $this->data[$this->idField])
			? $this->data[$this->idField]
			: false;
	}

	public function deleteById($id)
	{
		$id = (int)$id;
		return self::baseDelete(array($this->idField => $id), array($this->idField));
	}

	public function deleteByQuery($query, $data = null)
	{
		$query = 'DELETE FROM '.$this->mainTable.' WHERE '.$query;
		return db\Db::getMysql()->query($query,$data);
	}

	protected function _addRecord($table, $data)
	{
		$query = 'INSERT INTO `'.$table.'`(`'.join('`, `', array_keys($data)).'`)
				VALUES(\'' . join("', '", array_fill(0, sizeof($data), '?s')) . '\')';
		return ( db\Db::getMysql()->query($query,$data) ) ? $this->lastInsertId() : false ;
	}

	protected function _editRecord($table, $data, $idField, $field = null)
	{
		$field = $this->getDefaultIdField($field);
		$id = $data[$idField];
		unset($data[$idField]);
		$query = 'UPDATE '.$table.' SET';
		foreach (array_keys($data) as $k) {
			$query .= ' `'.$k.'`=\'?s\',';
		}
		$query = substr($query, 0, -1);
		$query .= ' WHERE `'.$field.'` = ?d';
		array_push($data,$id);
		return db\Db::getMysql()->query($query,$data)? $id: false;
	}

	protected function getDefaultIdField($idField)
	{
		return (empty($idField)) ? $this->idField : $idField;
	}

	protected function _deleteRecord($table, $value, $field = null)
	{
		$field = $this->getDefaultIdField($field);
		$query = 'DELETE FROM '.$table.' WHERE '.$field.'=?d LIMIT 1';
		return db\Db::getMysql()->query($query,array($value));
	}

	public function setData($data)
	{
		$this->data = ($data instanceof ArrayWrapper) ? $data->getArray() : $data;
		if (!empty($this->data))
			foreach ($this->data as $key=>$dat) {
				if (!in_array($key,$this->dataVar)) unset($this->data[$key]);
			}
		return true;
	}

	protected function _setDataVar($dataVar = array())
	{
		if (!empty($dataVar)) $this->dataVar = $dataVar;
		return $this;
	}

	protected function _setRules($data_set)
	{
		foreach ($this->rules() as $key=>$rule) {
			foreach (explode(',', $key) as $k) $this->dataRules[trim($k)] = $rule;
		}
		if (!empty($data_set)) foreach ($data_set as $key=>$d) {
			$this->dataRules[$key] = $d;
		}
		return $this;
	}

	protected function isFieldExist($elementId, $field = null)
	{
		$field = (isset($field)) ? $field : $this->idField;
		return db\Db::getMysql()->isExist($this->mainTable,$field,(string)$elementId);
	}

	protected function lastInsertId()
	{
		return db\Db::getMysql()->lastInsertId();
	}

	public function getField($field, $value, $idField = null, $table = null)
	{
		if (empty($field))
			throw new \Exception('Field name is empty in '.get_class($this).'!');
		$idField = (empty ($idField)) ? $this->idField : $idField;
		$table = (empty ($table)) ? $this->mainTable : $table;
		$query = 'SELECT `?s` FROM `?s` WHERE `?s`="?s"';
		$data = array ($field,$table,$idField,$value);
		$result = db\Db::getMysql()->rowNum($query, $data);
		return (is_array($result)) ? array_shift($result): false;
	}

	public function getMaxId ()
	{
		return DB::getMaxId($this->mainTable);
	}

	public function getNextId ()
	{
		return db\Db::getNextId($this->mainTable);
	}

	public function getMinFieldByIdField($field, $idFieldValue, $idField=null, $table = null)
	{
		if (empty($field))
			throw new \Exception('Field name is empty in '.get_class($this).'!');
		$table = (empty ($table)) ? $this->mainTable : $table;
		$query = 'SELECT MIN(`?s`) as `res` FROM `?s` WHERE `?s` = ?s';
		$data = array ($field, $table, $idField, $idFieldValue);
		$result = db\Db::getMysql()->row($query, $data);
		return (is_numeric($result['res'])) ? $result['res'] : false;
	}

	public function getNearestNumericValueDown($field, $value, $idFieldValue=null, $idField=null, $table = null)
	{
		$value = (double)$value;
		if (empty($field))
			throw new \Exception('Field name is empty in '.get_class($this).'!');
		$table = (empty ($table)) ? $this->mainTable : $table;
		$idFieldValue = (empty ($idFieldValue)) ? 1 : $idFieldValue;
		$idField = (empty ($idField)) ? 1 : $idField;
		$query = 'SELECT MAX(`?s`) as `res` FROM `?s` WHERE `?s`= ?s AND `?s`<=?s';
		$data = array ($field, $table, $idField, $idFieldValue, $field, $value);
		$result = db\Db::getMysql()->row($query, $data);
		return (is_numeric($result['res'])) ? $result['res'] : false;
	}

	public function getFieldWhereCriteria($field, $value, $idField, $criteriaValue, $criteriaField, $table = null)
	{
		if (empty($field))
			throw new \Exception('Field name is empty in '.get_class($this).'!');
		$table = (empty ($table)) ? $this->mainTable : $table;
		$query = 'SELECT `?s` FROM `?s` WHERE `?s`="?s"  AND  `?s`="?s"';
		$data = array ($field, $table, $idField, $value, $criteriaField, $criteriaValue);
		$result = db\Db::getMysql()->rowNum($query, $data);
		return (is_array($result)) ? array_shift($result): false;
	}

}