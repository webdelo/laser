<?php
namespace modules\catalog;
abstract class CatalogGoodObject extends \core\modules\base\ModuleObject
{
	private $goodInfo;

	function __construct($objectId, $config)
	{
		parent::__construct($objectId, $config);
	}

	public function getCode()
	{
		return $this->getGoodField('code');
	}

	private function loadGoodInfo()
	{
		if (empty($this->goodInfo))
			$this->goodInfo = $this->getCatalogFactory()->getGoodInfoById($this->id);
		return $this;
	}

	private function getCatalogFactory()
	{
		return \modules\catalog\CatalogFactory::getInstance();
	}

	private function getGoodField($field)
	{
		return $this->loadGoodInfo()->goodInfo[$field];
	}

	public function getClass()
	{
		return $this->getGoodField('class');
	}

	public function getName()
	{
		return $this->getGoodField('name');
	}

	public function edit($data = null, $fields = array(), $rules = array())
	{
		$catalogFactoryFields = array('name', 'code');
		$actualCatalogFactoryFields = $this->getActualFields($data, $fields, $catalogFactoryFields);
		if ($this->_beforeChangeWithoutAdapt($data, $fields)){
			if (array_intersect($actualCatalogFactoryFields, $catalogFactoryFields)) {
				if (!$this->getCatalogFactory()->edit($data, $actualCatalogFactoryFields))
					$this->setErrorsFromCatalogFactory();
			}
			return parent::edit($data, $fields);
		}
		return false;
	}

	protected function setErrorsFromCatalogFactory()
	{
		foreach ($this->getCatalogFactory()->getErrors() as $key => $value) {
			$this->addError($key, $value);
		}
		return $this;
	}

}