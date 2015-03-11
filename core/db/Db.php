<?php
namespace core\db;
class Db
{
	static private $db;
	static private $instance = NULL;
	static public $query_count;
	private $error_mode;
	private $query;
	private $data;
	private $database;

	private function __construct()
	{
		$db_config = \core\Configurator::getInstance()->db->getArrayByKey('settings');
		$this->database = $db_config['database'];
		$this->connect($db_config['host'],$db_config['login'],$db_config['pass'],$db_config['database']);
		$this->setErrorMode($db_config['error_mode']);
	}

	private function connect ($host,$login,$pass,$database)
	{
		self::$db = mysql_connect($host,$login,$pass) or $this->errorAction('error mysql connection');
		mysql_select_db($database,self::$db) or $this->errorAction('error DB select '.$this->errorMessage());

		 mysql_query("set names 'utf8'");

		/*
		mysql_query ("set character_set_client='utf-8'");
		mysql_query ("set character_set_results='utf-8'");
		mysql_query ("set collation_connection='utf-8_general_ci'");
		*/
	}

	/* singletone.
	 * access to class methods:
	 * Db::getMysql()->method()
	 */
	public static function getMysql()
	{
		return (self::$instance !== NULL) ? self::$instance : (self::$instance = new Db);
	}

	// return MySQL query result
	public function query($query, $data = array())
	{
		if (!empty($data)) {
			$this->data = array_map("mysql_real_escape_string",array_values($data));
			$this->data_num = 0;
			//$query = vsprintf($query,$data);
			$query = preg_replace_callback("/(\?s)|(\?d)/",array($this,'convertPlaceholders'),$query);
		}
		$this->query = $query;
		$result = mysql_query($query,self::$db) or $this->errorAction();


// log sql on start
//		$time_start = microtime(true);
//		$result = mysql_query($query,self::$db) or $this->errorAction();
//		$time_end = microtime(true);
//		$time = $time_end - $time_start;
//		$this->log($query, $time);
// log sql on end




		self::$query_count++;
		return $result;
	}

// log sql on start
	private function log($query, $time){
		if ($_SERVER['REMOTE_ADDR'] == '31.31.22.174') {
			$logFile = DIR.'sqlLog/sqlLog.txt';
			$fp=fopen($logFile, 'a');
			fwrite($fp, "\r\n" . str_replace(array("\r\n", "\r", "\n",  "   ", "\t", "\t\t"), ' ', $query) . ';' . sprintf('%.8f', $time));
			fclose($fp);
		}
	}
// log sql on end

	private function convertPlaceholders($type)
	{
		if (!isset($this->data[$this->data_num])) $this->errorAction('Too few arguments');
		switch($type[0]) {
			case '?d':
				$value = intval($this->data[$this->data_num]);
				break;
			case '?s':
				$value = $this->data[$this->data_num];
				break;
		}
		$this->data_num++;
		return $value;
	}

	// return array with single row
	public function row($query, $data = array())
	{
		return mysql_fetch_array($this->query($query, $data));
	}

	// return numeric array with single row
	public function rowNum($query, $data = array())
	{
		return mysql_fetch_row($this->query($query, $data));
	}

	// return associative array with single row
	public function rowAssoc($query, $data = array())
	{
		return mysql_fetch_assoc($this->query($query, $data));
	}

	// return multidimensional array with multiple rows
	public function rows($query, $data = array())
	{
		$rows = array();
		$result = $this->query($query, $data);
		while ($row = mysql_fetch_array($result)) $rows[] = $row;
		return $rows;
	}

	// return multidimensional numeric array with multiple rows
	public function rowsNum($query, $data = array())
	{
		$rows = array();
		$result = $this->query($query, $data);
		while ($row = mysql_fetch_row($result)) $rows[] = $row;
		return $rows;
	}

	// return multidimensional associative array with multiple rows
	public function rowsAssoc($query, $data = array())
	{
		$rows = array();
		$result = $this->query($query, $data);
		while ($row = mysql_fetch_assoc($result)) $rows[] = $row;
		return $rows;
	}

	// checks if isset $fild whith $data value in $table
	public function isExist($table,$field,$data)
	{
		$row = $this->rowNum("SELECT COUNT(*) FROM ".$table." WHERE ".$field."='?s'",array($data));
		if ($row[0] == 0)  return false;
		else return true;
	}

	public function getValue($field, $query, $data = array())
	{
		$row = mysql_fetch_assoc($this->query($query, $data));
		return $row[$field];
	}

	public function queryCount($q_count,$data = array())
	{
		$q = 'SELECT COUNT(*) as quantity FROM '.$q_count;
		$row = $this->row($q,$data);
		return $row['quantity'];
	}

	// return last INSERT operation ID
	public function lastInsertId()
	{
		return mysql_insert_id();
	}

	// return tables list in the current database
	public function tablesList()
	{
		$result = mysql_list_tables($this->database);
		while ($row = mysql_fetch_row($result)) $rows[] = $row;
		return $rows;
	}

	public function getLastQuery()
	{
		return $this->query;
	}

	public function getTableFields($table)
	{
		$result = mysql_list_fields($this->database, $table, self::$db);
		$columns = mysql_num_fields($result);
		for ($i = 0; $i < $columns; $i++) {
			$rows[] = mysql_field_name($result, $i);
		}
		return $rows;
	}

	// return error message
	public function errorMessage()
	{
		if ($this->error_mode == 2) return mysql_error().' SQL: '.$this->query;
		if ($this->error_mode == 1) return 'MySQL Error';
	}

	public function setErrorMode($mode)
	{
		$mode = intval($mode);
		if ($mode == 0) $mode = 1;
		if ($mode > 2) $mode = 2;
		$this->error_mode = $mode;
	}

	private function errorAction($message = false)
	{
		if ($this->error_mode == 1) exit('MySQL Error');
		echo '<pre>';
		if (!$message) throw new \Exception($this->errorMessage());
		else throw new \Exception($message);
		echo '</pre>';
	}

	public function str_replace_DB($table, $field, $search, $replace)
	{
		foreach($this->rows('SELECT `id`, `'.$field.'` FROM `'.$table.'` WHERE 1=1') as $key=>$text) {
			$text[$field] = str_replace($search, $replace, $text[$field]);
			$query = 'UPDATE '.$table.' SET `'.$field.'`= \'?s\' WHERE `id` = \'?d\'';
			$res = Db::getMysql()->query($query, array($field=>$text[$field], 'id'=>$text['id']));
			echo $res.' <br />';
		}

	}

	public function affectedRows()
	{
		return mysql_affected_rows(self::$db);
	}

	static public function getMaxId ($table)
	{
		return Db::getMaxField('id', $table);
	}

	static public function getMaxField ($field, $table)
	{
		$value = Db::getMysql()->rowAssoc('SELECT MAX(`'.mysql_real_escape_string($field).'`) FROM `'.mysql_real_escape_string($table).'`');
		return array_shift($value);
	}

	static public function getNextId ($table)
	{
		$maxId = (int)DB::getMaxId($table);
		return $maxId + 1;
	}

}
?>