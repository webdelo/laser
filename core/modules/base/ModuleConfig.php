<?php
namespace core\modules\base;
abstract class ModuleConfig implements \interfaces\IModuleConfig
{
	protected $table;
	protected $tablePostfix;
	protected $removePostfixFlag = false;
	protected $objectFields = array();
	protected $errorsList;
	protected $errors;
	protected $data;
	protected $idField;
	protected $removedStatus;
	protected $objectClass;
	protected $objectsClass;

	private $parentConfig;

	public function __construct($parentConfig = null)
	{
		$this->setParentConfig($parentConfig)
			 ->setTable()
			 ->setKeysInFieldsArray();
	}

	private function setParentConfig($parentConfig = null)
	{
		if (isset($parentConfig)) {
			if (!is_object($parentConfig)) {
				throw new \Exception('Parent Config must be Object in class '.get_class($this).'!');
			}
			$this->parentConfig = $parentConfig;
			$this->checkParentConfig();
		}
		return $this;
	}

	private function checkParentConfig()
	{
		if (!$this->parentConfig->mainTable())
			throw new \Exception('No access to method mainTable() in parent configuration object in '.get_class($this).' class.');
	}

	private function setTable()
	{
		$this->table = (isset($this->parentConfig)) ? $this->parentConfig->mainTable().$this->tablePostfix : TABLE_PREFIX.$this->table;
		return $this;
	}

	private function setKeysInFieldsArray()
	{
		if (!empty($this->objectFields))
			$this->objectFields = array_combine($this->objectFields, $this->objectFields);
		return $this;
	}

	public function setModelData(&$data)
	{
		$this->data = &$data;
		return $this;
	}

	public function setModelErrors(&$errors)
	{
		$this->errors = &$errors;
		return $this;
	}

	public function setModelErrorList(&$errorsList)
	{
		$this->errorsList = &$errorsList;
		return $this;
	}

	public function getIdField()
	{
		return (isset($this->idField)) ? $this->idField : null;
	}

	public function getRemovedStatus()
	{
		return (empty($this->removedStatus)) ? 0 : (int)$this->removedStatus;
	}

	public function mainTable()
	{
		return $this->table;
	}

	private function checkTable()
	{
		if (empty($this->table))
			throw new \Exception('Do not set the table in '.get_class($this).' class!');
	}

	public function rules()
	{
		return array();
	}

	public function outputRules()
	{
		return array();
	}

	public function getObjectFields()
	{
		return $this->objectFields;
	}

	public function removePostfix()
	{
		if ($this->removePostfixFlag)
			return $this;
		$this->removePostfixFlag = true;
		return (empty($this->tablePostfix)) ? $this : $this->removePostfixFromTable();
	}

	private function removePostfixFromTable()
	{
		$this->table = substr($this->table, 0, -strlen($this->tablePostfix));
		return $this;
	}

	public function setError($key, $text = null)
	{
		$this->addError($key, $text);
		return $this;
	}

	public function addError($key, $value)
	{
		$this->errors[(string)$key] = (string)$value;
		return $this;
	}

	public function getParentConfig()
	{
		return $this->parentConfig;
	}

	public function getObjectClass()
	{
		return $this->objectClass;
	}

	public function getObjectsClass()
	{
		return $this->objectsClass;
	}

	public function getObjectClassName()
	{
		 $objectClassArray = explode('\\', $this->objectClass);
		 return end($objectClassArray);
	}

	public function getObjectsClassName()
	{
		$objectsClassArray = explode('\\', $this->objectsClass);
		 return end($objectsClassArray);
	}

	public function getNewObjects()
	{
		$objectsClass = $this->getObjectsClass();
		return new $objectsClass;
	}

	public function getAdminTemplateDir()
	{
		return property_exists($this, 'templates') ? DIR.$this->templates : 'modules/'.strtolower($this->getObjectsClass()).'/tpl/';
	}

	public function getImagesPath()
	{
		return property_exists($this, 'imagesPath') ? $this->templates : 'files/'.strtolower($this->getObjectsClass()).'/images/';
	}

	public function getErrorsList()
	{
		$objects = new $this->objectsClass();
		return $objects->getErrorsList();
	}
}