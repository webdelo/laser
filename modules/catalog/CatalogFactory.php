<?php
namespace modules\catalog;
class CatalogFactory extends \core\modules\base\ModuleAbstract
{
	use \core\traits\ObjectPool;

	static protected $instance = null;

	private $goodId;
	private $goodCode;
	private $goodInfo;

	private static function init()
	{
		self::$instance = new CatalogFactory();
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}

	function __construct()
	{
		parent::__construct();
	}

	public function checkGoodExists($id)
	{
		$this->goodInfo = $this->getInfoById($id);
		return $this->goodInfo;
		if (empty($this->goodInfo)) {
			return false;
		} else { return true; }
	}
	
	public function getFirstGoodByName($id)
	{
		$massive = $this->getInfoById($id);
		$name = $massive['name'];
		$objs = $this->getGoodsByNameOrCode($name);
		$i = 1;
		foreach ($objs as $obj) {
			if ($i == 1) {
				return $obj;
			}
			$i++;
		}
	}
	
	public function getGoodById($id)
	{
		return $this->setGoodId($id)
					->setGoodInfoById()
					->getGoodByInfo();
	}

	protected function setGoodId($id)
	{
		$id = (int)$id;
		if ($id) {
			$this->goodId = $id;
			return $this;
		}
		throw new \Exception('Good ID is null or not valid in '.get_class($this).'!');
	}

	protected function setGoodInfoById()
	{
		$this->goodInfo = $this->getInfoById($this->goodId);
		if (empty($this->goodInfo))
			throw new \Exception('Good with ID="'.$this->goodId.'" was not found in '.get_class($this).'!');
		return $this;
	}

	protected function getGoodByInfo()
	{
		if ($this->goodInfo['id'])
			return new $this->goodInfo['class']($this->goodInfo['id']);
		return false;
	}

	public function getGoodInfoById($id)
	{
		return $this->getInfoById($id);
	}

	public function getGoodByCode($code)
	{
		return $this->setGoodCode($code)
					->setGoodInfoByCode()
					->getGoodByInfo();
	}

	protected function setGoodCode($code)
	{
		$code = (string)$code;
		if (empty($code))
			throw new \Exception('Good ID is null or not valid in '.get_class($this).'!');
		$this->goodCode = $code;
		return $this;
	}

	protected function setGoodInfoByCode()
	{
		$filters = new \core\FilterGenerator();
		$filters->setSubquery('AND `code`="?s"', strtoupper($this->goodCode));
		$this->goodInfo = $this->getOne('*',$filters);
//		if (empty($this->goodInfo))
//			throw new \Exception('Good with code="'.$this->goodCode.'" was not found in '.get_class($this).'!');
		return $this;
	}

	public function getGoodByAlias($alias, $domainAlias=null)
	{
		$query = 'SELECT `objectId` FROM `tbl_catalog_domainsinfo` WHERE `alias`="?s" AND `domainAlias`="?s"';
		$result = \core\db\Db::getMysql()->rowAssoc($query, array($alias, $domainAlias));
		return empty($result['objectId']) ? false : $this->getGoodById($result['objectId']);
	}

	public function getGoodsByNameOrCode($nameOrCode, $domainAlias = null, $excludeStatuses = array())
	{
		$data = array();

		$query = '(SELECT `objectId` as `id` FROM `tbl_catalog_domainsinfo` WHERE (('.$this->getWordsTextQuery($nameOrCode, $data).') OR `code` LIKE "%?s%") AND `domainAlias`="?s" ' ;
		$query .= ' ) ';

		$data = array_merge($data, array($nameOrCode, $domainAlias));

		if (empty($domainAlias)){
			$query .= ' UNION (SELECT `id` FROM `'.$this->mainTable().'` WHERE ('.$this->getWordsTextQuery($nameOrCode, $data).') OR `code` LIKE "%?s%")';
			$data = array_merge($data, array($nameOrCode));
		}

		$result = \core\db\Db::getMysql()->rowsAssoc($query, $data);
		$objects = array();
		foreach ($result as $value) {
			$objects[] = $this->getGoodById($value['id']);
		}
		return $objects;
	}

	private function getWordsTextQuery($text, &$data)
	{
		$words = explode(' ', $text);
		$data = empty($data) ? array() : $data;
		$query = '1=1';
		foreach ($words as $word){
			$query .= ' AND `name` LIKE "%?s%"';
			$data[] = $word;
		}
		return $query;
	}

	public function add($code, $name, $class)
	{
		if ($this->codeExists($code))
			throw new \exceptions\ExceptionUserFactory('Good with code "'.$code.'" is already exists!', 64);
		$data = array(
			'id'      => $this->getMaxId() + 1,
			'code'  => $code,
			'name'  => $name,
			'class' => (string)$class,
		);
		return ($this->baseAdd($data)) ? $this->lastInsertId() : false;
	}

	public function codeExists($code)
	{
		return $this->isFieldExist(strtoupper($code), 'code');
	}

	public function getIdByCode($code)
	{
		return $this->getField('id', $code, 'code');
	}

	public function getLastId()
	{
		return $this->getMaxId();
	}

	public function edit($goodData, $fields = array())
	{
		$fields = empty($fields) ? $this->getConfig()->getObjectFields() : array_merge($fields, array('id'));
		return $this->baseEdit($goodData, $fields);
	}
}
